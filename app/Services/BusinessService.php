<?php

namespace App\Services;

use App\CustomExceptions\InvalidRegistrationException;
use App\CustomExceptions\ObjectNotFoundException;
use App\Exceptions\NotAuthorizedException;
use App\Repositories\BusinessRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\MenuRepository;
use App\Utils\Validator;
use CodeIgniter\Files\File;
use chillerlan\QRCode\{QRCode, QROptions};


class BusinessService 
{
    private static function validateCategoryData($categoryData) {
        $rules = [
            'category_name' => 'required|min_length[3]|max_length[255]',
        ];

        $validationResult = Validator::validate($rules, [], $categoryData);

        if(!($validationResult === TRUE)) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    private static function validateCategoryOwnership($business, $category) {
        if ($category->owning_business_id !== $business->business_id) {
            throw new NotAuthorizedException(
                sprintf("Category with ID %s does not belong to Business with ID %s"), 
                $category->owning_business_id, 
                $business->business_id
            );
        }
    }

    public static function handleCategoryCreation($user, $categoryData) {
        self::validateCategoryData($categoryData);
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        CategoryRepository::createCategory($userBusiness, $categoryData);
    }

    public static function handleGetCategoryList($user, $search) {
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        $businessCategories = CategoryRepository::getCategoriesOfBusiness($userBusiness, $search ?? '');
        return [
            'business' => $userBusiness,
            'categories' => $businessCategories,
        ];
    }

    public static function handleUpdateCategory($user, $categoryData) {
        self::validateCategoryData($categoryData);
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        $updatedCategory = CategoryRepository::getCategoryByIDOrThrowException($categoryData['category_id']);
        self::validateCategoryOwnership($userBusiness, $updatedCategory);
        CategoryRepository::updateCategory($updatedCategory, $categoryData);
    }

    private static function validateMenuData($menuData, $menuImage) {
        $rules = [
            'name' => 'required|string|min_length[3]|max_length[255]',
            'price' => 'required|decimal|greater_than[0]',
            'description' => 'string' 
        ];

        $validationResult = Validator::validate($rules, [], $menuData);

        if(!($validationResult === TRUE)) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    private static function transformMenuData($menuData) {
        $dataToBeUpdated = [
            'name' => $menuData['name'],
            'price' => $menuData['price'],
            'is_available' => array_key_exists('is_available', $menuData),
        ];

        if(array_key_exists('description', $menuData)) {
            $dataToBeUpdated['description'] = $menuData['description'];
        }

        if(array_key_exists('category_id', $menuData) || !$menuData['category_id'] === 'others') {
            $dataToBeUpdated['category_id'] = $menuData['category_id'];
        }

        return $dataToBeUpdated;
    }

    private static function saveImageFile($businessID, $menuID, $menuImage) {
        $extension = pathinfo($menuImage->getName(), PATHINFO_EXTENSION);
        $fileName = "$businessID-$menuID.$extension";
        $menuImage->move(WRITEPATH . 'menu_images', $fileName);
        MenuRepository::updateMenuImage($menuID, $fileName);
    }

    private static function removeImageFileOfMenu($menu) {
        if ($menu->image_url !== NULL) {
            unlink(WRITEPATH . 'menu_images/' . $menu->image_url);
        }
    }

    public static function handleMenuCreation($user, $menuData, $menuImage) {
        self::validateMenuData($menuData, $menuImage);
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        $transformedMenuData = self::transformMenuData($menuData);
        $createdMenuID = MenuRepository::createMenu($userBusiness, $transformedMenuData);
        if ($menuImage->isValid()) {
            self::saveImageFile($userBusiness->business_id, $createdMenuID, $menuImage);
        }
    }

    private static function transformMenuListRequestData($requestData) {
        $menuName = $requestData['menu_name'] ?? "";
        $categoryID = $requestData['category_id'] ?? "all";
        $page = (int) ($requestData['page'] ?? 1);

        if ($categoryID === "others") {
            $categoryID = NULL;
        }

        return [
            'name' => $menuName,
            'category_id' => $categoryID,
            'page' => $page,
        ];
    }

    public static function handleBusinessMenuList($user, $requestData) {
        $transformedRequestData = self::transformMenuListRequestData($requestData);
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        $businessCategories = CategoryRepository::getCategoriesOfBusiness($userBusiness, "");
        $businessMenusPaginated = MenuRepository::getPaginatedMenuItemsOfBusinessMatchingNameAndCategory(
            $userBusiness->business_id, 
            $transformedRequestData['name'], 
            $transformedRequestData['category_id'],
            FALSE,
            12,
            $transformedRequestData['page'],
        );
        
        return[
            'menus' => $businessMenusPaginated['result'],
            'pager' => $businessMenusPaginated['pager'],
            'categories' => $businessCategories
        ];
    }

    private static function validateMenuOwnership($business, $menu) {
        if ($menu->owning_business_id !== $business->business_id) {
            throw new NotAuthorizedException("Menu with ID {$menu->menu_id} do not belong to business with ID {$business->business_id}");
        }
    }

    public static function handleBusinessGetMenuData($user, $menuID) {
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        $menu = MenuRepository::getMenuByIDOrThrowException($menuID);
        self::validateMenuOwnership($userBusiness, $menu);

        $businessCategories = CategoryRepository::getCategoriesOfBusiness($userBusiness, '');
        return [
            'menu' => $menu,
            'categories' => $businessCategories,
        ];
    }

    public static function handleMenuGetImage($menuID) {
        $menu = MenuRepository::getMenuByIDOrThrowException($menuID);
        $menuImageFileName = $menu->image_url;

        if ($menuImageFileName !== NULL) {
            $menuImageFullPath = WRITEPATH . 'menu_images/' . $menuImageFileName;
            $menuImageFile = new File($menuImageFullPath, TRUE);
            return $menuImageFile;

        } else {
            throw new ObjectNotFoundException("Menu with ID $menuID does not have an image");
        }
    }

    public static function handleMenuEdit($user, $menuID, $menuData, $menuImage) {
        self::validateMenuData($menuData, $menuImage);
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        $menu = MenuRepository::getMenuByIDOrThrowException($menuID);
        self::validateMenuOwnership($userBusiness, $menu);
        $transformedMenuData = self::transformMenuData($menuData);
        print_r($transformedMenuData);
        Menurepository::updateMenu($menuID, $transformedMenuData);

        if ($menuImage->isValid()) {
            self::removeImageFileOfMenu($menu);
            self::saveImageFile($userBusiness->business_id, $menuID, $menuImage);
        }
    }

    public static function handleGetBusinessTableData($user, $requestData) {
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        
        return [
            'business' => $userBusiness,
            'current_page' => (int) ($requestData['page'] ?? 1),
            'total_pages' => (int) ceil($userBusiness->num_of_tables / 10),
            'searched_table_number' => !array_key_exists('search', $requestData) || $requestData['search'] === "" ?  NULL : (int) $requestData['search']
        ];
    }

    public static function handleGetTableQR($businessID, $tableNumber) {        
        $tableQRURL = base_url("customer/order/menu/$businessID/$tableNumber");
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'scale' => 15,
            'outputBase64' => false,
        ]);
        $qrcode = new QRCode($options);

        return $qrcode->render($tableQRURL);
    }

    private static function validateCapacityData($capacityData) {
        $rules = [
            'new_table_quantity' => 'required|is_natural_no_zero'
        ];

        $validationResult = Validator::validate($rules, [], $capacityData);

        if(!($validationResult === TRUE)) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    private static function fillCapacityDataWithRemainingBusinessData($business, $capacityData)
    {
        return [
            'business_name' => $business->business_name,
            'num_of_tables' => $capacityData['new_table_quantity'],
            'address' => $business->address,
            'is_open' => $business->is_open,
            'business_is_archived' => $business->business_is_archived,
        ];
    }

    public static function handleUpdateBusinessTableCapacity($user, $capacityData) {
        self::validateCapacityData($capacityData);
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        $transformedCapacityData = self::fillCapacityDataWithRemainingBusinessData($userBusiness, $capacityData);
        BusinessRepository::updateBusinessData($userBusiness->business_id, $transformedCapacityData);
    }
}