<?php

namespace App\Services;

use App\CustomExceptions\InvalidRegistrationException;
use App\CustomExceptions\ObjectNotFoundException;
use App\CustomExceptions\NotAuthorizedException;
use App\Repositories\BusinessRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\MenuRepository;
use App\Utils\Utils;
use App\Utils\Validator;
use CodeIgniter\Files\File;
use chillerlan\QRCode\{QRCode, QROptions};


class BusinessService
{
    public static function getBusinessByUserOrNonAuthorized($user)
    {
        try {
            return BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        } catch (ObjectNotFoundException $exception) {
            throw new NotAuthorizedException($exception->getMessage());
        }
    }

    private static function validateCategoryData($categoryData)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
        ];

        $validationResult = Validator::validate($rules, [], $categoryData);

        if ($validationResult !== TRUE) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    private static function validateCategoryOwnership($businessID, $category)
    {
        if ($category->owning_business_id !== $businessID) {
            throw new NotAuthorizedException(
                sprintf("Category with ID %s does not belong to Business with ID %s"),
                $category->owning_business_id,
                $businessID
            );
        }
    }

    public static function handleCategoryCreation($user, $categoryData)
    {
        self::validateCategoryData($categoryData);
        $transformedCategoryData = Utils::trimAllString($categoryData);
        CategoryRepository::createCategory($user->business_id, $transformedCategoryData);
    }

    public static function handleGetCategoryList($user, $requestData)
    {
        $businessCategoriesPaginated = CategoryRepository::getPaginatedCategoriesOfBusiness(
            $user->business_id, 
            $requestData['search'] ?? '', 
            10, 
            (int) ($requestData['page'] ?? 1),
            TRUE
        );

        return [
            'categories' => $businessCategoriesPaginated['result'],
            'search' => $requestData['search'] ?? '',
            'pager' => $businessCategoriesPaginated['pager'],
        ];
    }

    public static function handleUpdateCategory($user, $categoryData)
    {
        self::validateCategoryData($categoryData);
        $updatedCategory = CategoryRepository::getCategoryByIDOrThrowException($categoryData['category_id']);
        self::validateCategoryOwnership($user->business_id, $updatedCategory);
        $transformedCategoryData = Utils::trimAllString($categoryData);
        CategoryRepository::updateCategory($updatedCategory->category_id, $transformedCategoryData);
    }

    private static function validateMenuData($menuData, $menuImage)
    {
        $rules = [
            'name' => 'required|string|min_length[3]|max_length[255]',
            'price' => 'required|decimal|greater_than[0]',
            'description' => 'string'
        ];

        $validationResult = Validator::validate($rules, [], $menuData);

        if ($validationResult !== TRUE) {
            throw new InvalidRegistrationException($validationResult);
        }

        // TODO: Validate image is of image type
    }

    private static function transformMenuData($menuData)
    {
        $dataToBeUpdated = [
            'name' => trim($menuData['name']),
            'price' => $menuData['price'],
            'is_available' => array_key_exists('is_available', $menuData),
        ];

        if (array_key_exists('description', $menuData) && !empty(trim($menuData['description']))) {
            $dataToBeUpdated['description'] = trim($menuData['description']);
        }

        if (array_key_exists('category_id', $menuData) && $menuData['category_id'] !== 'others') {
            $dataToBeUpdated['category_id'] = $menuData['category_id'];
        }

        return $dataToBeUpdated;
    }

    private static function saveImageFile($businessID, $menuID, $menuImage)
    {
        $extension = pathinfo($menuImage->getName(), PATHINFO_EXTENSION);
        $fileName = "$businessID-$menuID.$extension";
        $menuImage->move(WRITEPATH . 'menu_images', $fileName);
        MenuRepository::updateMenuImage($menuID, $fileName);
    }

    private static function removeImageFileOfMenu($menu)
    {
        if (!is_null($menu->image_url)) {
            unlink(WRITEPATH . 'menu_images/' . $menu->image_url);
        }
    }

    public static function handleMenuCreation($user, $menuData, $menuImage)
    {
        self::validateMenuData($menuData, $menuImage);
        $transformedMenuData = self::transformMenuData($menuData);
        $createdMenuID = MenuRepository::createMenu($user->business_id, $transformedMenuData);
        if ($menuImage->isValid()) {
            self::saveImageFile($user->business_id, $createdMenuID, $menuImage);
        }
    }

    private static function transformMenuListRequestData($requestData)
    {
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

    public static function handleBusinessMenuList($user, $requestData)
    {
        $transformedRequestData = self::transformMenuListRequestData($requestData);
        $businessCategories = CategoryRepository::getCategoriesOfBusiness($user->business_id, "");
        $businessMenusPaginated = MenuRepository::getPaginatedMenuItemsOfBusinessMatchingNameAndCategory(
            $user->business_id,
            $transformedRequestData['name'],
            $transformedRequestData['category_id'],
            FALSE,
            12,
            $transformedRequestData['page'],
        );

        return [
            'menus' => $businessMenusPaginated['result'],
            'pager' => $businessMenusPaginated['pager'],
            'categories' => $businessCategories,
            'search' => $requestData['menu_name'] ?? '',
            'category_id' => $requestData['category_id'] ?? '',
        ];
    }

    private static function validateMenuOwnership($businessID, $menu)
    {
        if ($menu->owning_business_id !== $businessID) {
            throw new NotAuthorizedException("Menu with ID {$menu->menu_id} do not belong to business with ID $businessID");
        }
    }

    public static function handleBusinessGetMenuData($user, $menuID)
    {
        $menu = MenuRepository::getMenuByIDOrThrowException($menuID);
        self::validateMenuOwnership($user->business_id, $menu);

        $businessCategories = CategoryRepository::getCategoriesOfBusiness($user->business_id, '');
        return [
            'menu' => $menu,
            'categories' => $businessCategories,
        ];
    }

    public static function handleMenuGetImage($menuID)
    {
        $menu = MenuRepository::getMenuByIDOrThrowException($menuID);
        $menuImageFileName = $menu->image_url;

        if (!is_null($menuImageFileName)) {
            $menuImageFullPath = WRITEPATH . 'menu_images/' . $menuImageFileName;
            $menuImageFile = new File($menuImageFullPath, TRUE);

            return [
                'base_name' => $menuImageFileName,
                'mime_type' => $menuImageFile->getMimeType(),
                'content' => readfile($menuImageFullPath),
            ];
        } else {
            throw new ObjectNotFoundException("Menu with ID $menuID does not have an image");
        }
    }

    public static function handleMenuEdit($user, $menuID, $menuData, $menuImage)
    {
        self::validateMenuData($menuData, $menuImage);
        $menu = MenuRepository::getMenuByIDOrThrowException($menuID);
        self::validateMenuOwnership($user->business_id, $menu);
        $transformedMenuData = self::transformMenuData($menuData);
        Menurepository::updateMenu($menuID, $transformedMenuData);

        if ($menuImage->isValid()) {
            self::removeImageFileOfMenu($menu);
            self::saveImageFile($user->business_id, $menuID, $menuImage);
        }
    }

    public static function handleGetBusinessTableData($user, $requestData)
    {
        $userBusiness = BusinessRepository::getBusinessById($user->business_id);

        return [
            'business' => $userBusiness,
            'current_page' => (int) ($requestData['page'] ?? 1),
            'total_pages' => (int) ceil($userBusiness->num_of_tables / 10),
            'searched_table_number' => !array_key_exists('search', $requestData) || empty($requestData['search']) ?  NULL : (int) $requestData['search']
        ];
    }

    public static function handleGetTableQR($businessID, $tableNumber)
    {
        $tableQRURL = base_url("customer/order/menu/$businessID/$tableNumber");
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'scale' => 15,
            'outputBase64' => false,
        ]);
        $qrcode = new QRCode($options);

        return $qrcode->render($tableQRURL);
    }

    private static function validateCapacityData($capacityData)
    {
        $rules = [
            'new_table_quantity' => 'required|is_natural_no_zero'
        ];

        $validationResult = Validator::validate($rules, [], $capacityData);

        if ($validationResult !== TRUE) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    public static function handleUpdateBusinessTableCapacity($user, $capacityData)
    {
        self::validateCapacityData($capacityData);
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        BusinessRepository::updateBusiness(
            $userBusiness->business_id,
            [
                'num_of_tables' => $capacityData['new_table_quantity']
            ]
        );
    }
}
