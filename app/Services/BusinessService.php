<?php

namespace App\Services;

use App\CustomExceptions\InvalidRequestException;
use App\CustomExceptions\ObjectNotFoundException;
use App\CustomExceptions\NotAuthorizedException;
use App\Repositories\BusinessRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\MenuRepository;
use App\Utils\Utils;
use App\Utils\Validator;
use CodeIgniter\Files\File;
use chillerlan\QRCode\{QRCode, QROptions};

/**
 * Service to deal with business logic for business related operations
 */
class BusinessService
{
    /**
     * Handle the retrieval of a business profile affiliated with a given user.
     *
     * @param object $user The user object for whom to retrieve the business profile.
     * @return array An array containing the retrieved business record.
     */
    public static function handleGetBusinessProfile($user) {
        // Retrieve the business information based on the user's business ID
        $userBusiness = BusinessRepository::getBusinessByID($user->business_id);
        
        return [
            'business' => $userBusiness
        ];
    }

    /**
     * Validate business data for registration.
     *
     * @param array $businessData An associative array containing business data to be validated.
     * @throws InvalidRequestException When validation fails, this exception is thrown with the validation errors.
     */
    public static function validateBusinessData($businessData)
    {
        // Define validation rules for business data
        $rules = [
            'business_name' => 'required|string|min_length[3]|max_length[255]',
            'num_of_tables' => 'required|is_natural',
            'address' => 'required|string'
        ];

        // Define error messages for validation rules
        $errors = [
            'business_name' => [
                'required' => 'Business name is required.',
                'min_length' => 'Business name length must be between 3 and 255',
                'max_length' => 'Business name length must be between 3 and 255',
            ],
            'num_of_tables' => [
                'required' => 'Number of tables is required.',
                'is_natural' => 'Number of tables must be a natural number (greater than or equal to 0)',
            ],
            'address' => [
                'required' => 'Business address is required.'
            ]
        ];

        // Perform validation
        $validationResult = Validator::validate($rules, $errors, $businessData);
        if ($validationResult !== TRUE) {
            throw new InvalidRequestException($validationResult);
        }
    }

    /**
     * Check if a user has associated business.
     *
     * @param object $user The user object to check for affiliated business.
     * @return bool Returns true if the user has an affiliated business, otherwise returns false.
     */
    public static function userHasBusiness($user) {
        $associatedBusiness = BusinessRepository::getBusinessByUserID($user->id);
        return !is_null($associatedBusiness);
    }

    /**
     * Validate user's eligibility to create a business.
     *
     * @param object $creatingUser The user object to check for business eligibility.
     * @throws InvalidRequestException When the user is not eligible to create a business (already has one).
     */
    private static function validateUserBusinessEligibility($creatingUser)
    {
        // Check if the user already has an associated business
        if (self::userHasBusiness($creatingUser)) {
            // If the user has a business associated, throw an exception
            throw new InvalidRequestException("A user can only have one business.");
        }
    }

    /**
     * Transform business data to match database field names.
     * This method also sanitize input to prevent user from modifying other role-managed fields.
     *
     * @param array $businessData The business data to be transformed.
     * @param bool $isModifiedByAdmin Indicates whether the data is modified by an admin (optional).
     * @return array The transformed business data in a standardized format.
     */
    public static function transformBusinessData($businessData, $isModifiedByAdmin = FALSE) {
        // Initialize the transformed business data array
        $transformedBusinessData = [
            'business_name' => $businessData['business_name'],
            'address' => $businessData['address'],
            'num_of_tables' => $businessData['num_of_tables'],
        ];

        if ($isModifiedByAdmin) {
            // Include admin managed fields if action is initiated by admin
            $transformedBusinessData['business_is_archived'] = $businessData['business_subscription_status'] === "archived";

        } else {
            // Include is_open field to indicate whether business is open if action is initiated by owner of business (non-admin)
            $transformedBusinessData['is_open'] = array_key_exists('is_open', $businessData);
        }

        // Return the transformed business data
        return $transformedBusinessData;
    }

    /**
     * Handle the registration of a new business.
     *
     * @param object $user The user object representing the logged-in user, initiating the business registration.
     * @param array $requestData The request data containing business details for registration.
     * @throws InvalidRequestException If data is invalid or user business registration eligibility checks fails.
     */
    public static function handleRegisterBusiness($user, $requestData)
    {
        // Trim all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Validate the trimmed business data
        self::validateBusinessData($requestData);
        
        // Validate the user's eligibility to register a new business
        self::validateUserBusinessEligibility($user);
        
        // Transform the validated business data
        $transformedBusinessData = self::transformBusinessData($requestData);
        
        // Create a new business record in the database
        BusinessRepository::createBusiness($user->id, $transformedBusinessData);
    }

    /**
     * Handle the editing of business profile.
     *
     * @param object $user The user object representing the logged-in business, initiating the business profile edit.
     * @param array $requestData The request data containing updated business profile information.
     * @throws InvalidRequestException If submitted business data is invalid.
     */
    public static function handleBusinessEditProfile($user, $requestData) {
        // Trim all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Validate the trimmed business data
        self::validateBusinessData($requestData);
        
        // Transform the validated business data
        $businessData = self::transformBusinessData($requestData);
        
        // Update the business record in the database
        BusinessRepository::updateBusiness($user->business_id, $businessData);
    }

    /**
     * Validate category data.
     *
     * @param array $categoryData An associative array containing category data to be validated.
     * @param bool $forCreate Indicates whether the validation is for creation (optional, default is TRUE).
     * @throws InvalidRequestException When validation fails, this exception is thrown with the validation errors.
     */
    private static function validateCategoryData($categoryData, $forCreate = TRUE)
    {
        // Define validation rules for category data
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
        ];

        // Add 'category_id' rule if validation is for update
        if (!$forCreate) {
            $rules['category_id'] = 'required';
        }

        // Perform validation
        $validationResult = Validator::validate($rules, [], $categoryData);
        if ($validationResult !== TRUE) {
            throw new InvalidRequestException($validationResult);
        }
    }


    /**
     * Checks if the specified category belongs to the given business.
     *
     * @param string $businessID The ID of the business.
     * @param object $category The category object to check ownership.
     * @throws NotAuthorizedException If category does not belong to the specified business.
     */
    private static function validateCategoryOwnership($businessID, $category)
    {
        // Check if the owning business ID of the category matches the given business ID
        if ($category->owning_business_id !== $businessID) {
            throw new NotAuthorizedException(
                sprintf(
                    "Category with ID %s does not belong to Business with ID %s",
                    $category->owning_business_id,
                    $businessID
                ),
            );
        }
    }

    /**
     * Handle the creation of a new category.
     *
     * @param object $user The user object representing the logged-in business, initiating the category creation.
     * @param array $requestData The request data containing category details.
     * @throws InvalidRequestException If validation of category data fails during the creation process.
     */
    public static function handleCreateCategory($user, $requestData)
    {
        // Trim all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Validate the trimmed category data
        self::validateCategoryData($requestData);
        
        // Create a new category record in the database
        CategoryRepository::createCategory($user->business_id, $requestData);
    }

    /**
     * Handle the retrieval of a paginated list of categories associated with a user's business.
     *
     * @param object $user The user object representing the logged-in business.
     * @param array $requestData The request data containing search and pagination parameters.
     * @return array An array containing paginated categories, search parameter, and pager information.
     */
    public static function handleGetCategoryList($user, $requestData)
    {
        // Retrieve paginated categories associated with the user's business
        $businessCategoriesPaginated = CategoryRepository::getPaginatedCategoriesOfBusiness(
            $user->business_id,
            $requestData['search'] ?? '',
            TRUE,
            10,
            (int) ($requestData['page'] ?? 1),
        );

        // Return paginated categories, search parameter, and pager information
        return [
            'categories' => $businessCategoriesPaginated['result'],
            'search' => $requestData['search'] ?? '',
            'pager' => $businessCategoriesPaginated['pager'],
        ];
    }

    /**
     * Handle business' update of a category by a business.
     *
     * @param object $user The user object representing the logged-in business, updating the category.
     * @param array $requestData The request data containing updated category details.
     * @throws NotAuthorizedException If the user affiliated business does not own the category.
     * @throws InvalidRequestException If validation of category data fails during update process.
     * @throws ObjectNotFoundException If category matching ID does not exist.
     */
    public static function handleUpdateCategory($user, $requestData)
    {
        // Trim whitespace from all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Validate the category data for update
        self::validateCategoryData($requestData, FALSE);
        
        // Retrieve the category to be updated by its ID or throw an exception if not found
        $updatedCategory = CategoryRepository::getCategoryByIDOrThrowException($requestData['category_id']);
        
        // Validate if the user owns the category
        self::validateCategoryOwnership($user->business_id, $updatedCategory);
        
        // Update the category details
        CategoryRepository::updateCategory($updatedCategory->category_id, $requestData);
    }

    /**
     * Handle the deletion of a category by a business.
     *
     * @param object $user The user object representing the logged-in business, deleting the category.
     * @param array $requestData The request data containing the ID of the category to be deleted.
     * @throws NotAuthorizedException If the user does not own the category.
     * @throws ObjectNotFoundException If category matching ID does not exist.
     */
    public static function handleDeleteCategory($user, $requestData) {
        // Retrieve the category to be deleted by its ID or throw an exception if not found
        $deletedCategory = CategoryRepository::getCategoryByIDOrThrowException($requestData['category_id'] ?? '');
        
        // Validate if the user owns the category
        self::validateCategoryOwnership($user->business_id, $deletedCategory);
        
        // Delete the category
        CategoryRepository::deleteCategory($deletedCategory->category_id);
    }
    
    /**
     * Validate menu data for registration and update.
     *
     * @param array $menuData An associative array containing menu data to be validated.
     * @throws InvalidRequestException When validation fails, this exception is thrown with the validation errors.
     */
    private static function validateMenuData($menuData)
    {
        // Define validation rules for menu data
        $rules = [
            'name' => 'required|string|min_length[3]|max_length[255]',
            'price' => 'required|decimal|greater_than[0]',
            'description' => 'permit_empty|string'
        ];

        // Perform validation
        $validationResult = Validator::validate($rules, [], $menuData);
        if ($validationResult !== TRUE) {
            throw new InvalidRequestException($validationResult);
        }
    }

    /**
     * Validate file extension to ensure that it is an image.
     *
     * @param \CodeIgniter\HTTP\Files\UploadedFile $imageFile The uploaded file to be validated.
     * @throws InvalidRequestException If the image file extension is not allowed.
     */
    private static function validateImageFile($imageFile)
    {
        // Guess the file extension of the image file
        $fileExtension = $imageFile->guessExtension();

        // Check if the file extension is not one of the allowed types (jpg, jpeg, png)
        if (!in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
            throw new InvalidRequestException('Image file can only be either png or jpg');
        }
    }

    /**
     * Transform menu data to match database field names and format.
     * This method prepares the menu data for updating or inserting into the database.
     *
     * @param array $menuData The menu data to be transformed.
     * @return array The transformed menu data with standardized keys and values.
     */
    private static function transformMenuData($menuData)
    {
        // Initialize an array to hold the data to be updated
        $dataToBeUpdated = [
            'name' => $menuData['name'],
            'price' => $menuData['price'],
            'is_available' => array_key_exists('is_available', $menuData),
        ];

        // Include description if available
        if (array_key_exists('description', $menuData) && !empty($menuData['description'])) {
            $dataToBeUpdated['description'] = $menuData['description'];
        }

        // Include category ID if not 'others'
        if (array_key_exists('category_id', $menuData) && $menuData['category_id'] !== 'others') {
            $dataToBeUpdated['category_id'] = $menuData['category_id'];
        }

        // Return the transformed menu data
        return $dataToBeUpdated;
    }

    /**
     * Save the menu image file and update the menu entry in the database with the image URL.
     *
     * @param string $businessID The ID of the business owning the menu.
     * @param string $menuID The ID of the menu.
     * @param \CodeIgniter\HTTP\Files\UploadedFile $menuImage The uploaded menu image file.
     */
    private static function saveImageFile($businessID, $menuID, $menuImage)
    {
        // Extract the extension of the uploaded image file
        $extension = pathinfo($menuImage->getName(), PATHINFO_EXTENSION);

        // Construct the filename using business ID, menu ID, and file extension
        $fileName = "$businessID-$menuID.$extension";

        // Move the uploaded image file to the menu images directory
        $menuImage->move(WRITEPATH . 'menu_images', $fileName);

        // Update the menu entry in the database with the image URL
        MenuRepository::updateMenu($menuID, ['image_url' => $fileName]);
    }

    /**
     * Remove the image file associated with a menu if it exists.
     *
     * @param object $menu The menu object whose image wants to be removed.
     */
    private static function removeImageFileOfMenu($menu)
    {
        // Check if the menu has an associated image
        if (!is_null($menu->image_url)) {
            // Construct the path to the image file
            $filePath = WRITEPATH . 'menu_images/' . $menu->image_url;

            // Delete the image file from the server
            unlink($filePath);
        }
    }

    /**
     * Handle the creation of a new menu entry.
     *
     * @param object $user The user object representing the logged-in business, creating the menu.
     * @param array $requestData The request data containing information about the new menu.
     * @param \CodeIgniter\HTTP\Files\UploadedFile $menuImage The uploaded menu image file.
     * @throws InvalidRequestException If menu data or image validation fails during creation process.
     */
    public static function handleCreateMenu($user, $requestData, $menuImage)
    {
        // Start a transaction for database operations (to prevent data commit before the related image is saved)
        $db = \Config\Database::connect();
        $db->transStart();

        // Trim all string values in the request data
        $requestData = Utils::trimAllString($requestData);

        // Validate the menu data
        self::validateMenuData($requestData);

        // Transform the menu data to match database field names and format
        $transformedMenuData = self::transformMenuData($requestData);

        // Create the menu entry in the database
        $createdMenuID = MenuRepository::createMenu($user->business_id, $transformedMenuData);
        
        // Check if the uploaded image is valid and has not been moved
        if ($menuImage->isValid() && !$menuImage->hasMoved()) {
            // Validate the uploaded image file
            self::validateImageFile($menuImage);
            
            // Save the uploaded image file
            self::saveImageFile($user->business_id, $createdMenuID, $menuImage);
        }

        // Complete the transaction
        $db->transComplete();
    }

    /**
     * Standardize request data for menu listing.
     *
     * @param array $requestData The array containing the request data.
     * @return array Transformed request data.
     */
    private static function transformMenuListRequestData($requestData)
    {
        // Extract menu name from request data or default to an empty string if not provided
        $menuName = $requestData['menu_name'] ?? "";

        // Extract category ID from request data or default to "all" if not provided
        $categoryID = $requestData['category_id'] ?? "all";

        // Extract page parameter from request data, default to 1 if not provided
        $page = (int) ($requestData['page'] ?? 1);

        // If category ID is "others", set it to NULL to signify menus not in any category
        if ($categoryID === "others") {
            $categoryID = NULL;
        }

        // Return transformed request data
        return [
            'name' => $menuName,
            'category_id' => $categoryID,
            'page' => $page,
        ];
    }

    /**
     * Handle the retrieval of a paginated list of menus by a business.
     *
     * @param object $user The user object representing the logged-in business.
     * @param array $requestData The request data containing criteria for menu retrieval.
     * @return array An array containing menus, pager information, and business categories.
     */
    public static function handleGetMenuList($user, $requestData)
    {
        // Transform request data for menu listing
        $transformedRequestData = self::transformMenuListRequestData($requestData);

        // Retrieve menu categories of the user's business
        $businessCategories = CategoryRepository::getCategoriesOfBusiness($user->business_id, "");

        // Retrieve paginated menu items of the user's business based on request filters
        $businessMenusPaginated = MenuRepository::getPaginatedMenuItemsOfBusinessMatchingNameAndCategory(
            $user->business_id,
            $transformedRequestData['name'],
            $transformedRequestData['category_id'],
            FALSE,
            12,
            $transformedRequestData['page']
        );

        // Construct and return the response array
        return [
            'menus' => $businessMenusPaginated['result'],
            'pager' => $businessMenusPaginated['pager'],
            'categories' => $businessCategories,
            'search' => $requestData['menu_name'] ?? '',
            'category_id' => $requestData['category_id'] ?? '',
        ];
    }

    /**
     * Validate if business owns menu.
     *
     * @param string $businessID The ID of the business.
     * @param object $menu The menu object to be validated.
     * @throws NotAuthorizedException If the menu does not belong to the specified business.
     */
    private static function validateMenuOwnership($businessID, $menu)
    {
        if ($menu->owning_business_id !== $businessID) {
            throw new NotAuthorizedException("Menu with ID {$menu->menu_id} does not belong to the business with ID $businessID");
        }
    }

    /**
     * Handle the retrieval of menu data.
     *
     * @param object $user The user object representing the logged-in business.
     * @param string $menuID The ID of the menu to retrieve.
     * @return array An array containing menu and categories data.
     * @throws ObjectNotFoundException If the menu matching the ID does not exist.
     * @throws NotAuthorizedException If the user's business does not own the menu.
     */
    public static function handleGetMenuData($user, $menuID)
    {
        // Retrieve the menu by its ID or throw an exception if not found
        $menu = MenuRepository::getMenuByIDOrThrowException($menuID);
        
        // Validate if the user's business has ownership of the menu
        self::validateMenuOwnership($user->business_id, $menu);

        // Retrieve categories associated with the user's business
        $businessCategories = CategoryRepository::getCategoriesOfBusiness($user->business_id, '');
        
        // Return the menu and categories data
        return [
            'menu' => $menu,
            'categories' => $businessCategories,
        ];
    }

    /**
     * Handle retrieving the image associated with a menu.
     *
     * @param string $menuID The ID of the menu.
     * @return array An array containing the menu image related data.
     * @throws ObjectNotFoundException If the menu does not have an associated image.
     */
    public static function handleMenuGetImage($menuID)
    {
        // Retrieve the menu by its ID or throw an exception if not found
        $menu = MenuRepository::getMenuByIDOrThrowException($menuID);
        $menuImageFileName = $menu->image_url;

        if (!is_null($menuImageFileName)) {
            // Construct the full path of the menu image
            $menuImageFullPath = WRITEPATH . 'menu_images/' . $menuImageFileName;
            
            // Create a file object for the menu image
            $menuImageFile = new File($menuImageFullPath, TRUE);

            // Return the base name, MIME type, and content of the menu image
            return [
                'base_name' => $menuImageFileName,
                'mime_type' => $menuImageFile->getMimeType(),
                'content' => readfile($menuImageFullPath),
            ];

        } else {
            // Throw an exception if the menu does not have an associated image
            throw new ObjectNotFoundException("Menu with ID $menuID does not have an image");
        }
    }

    /**
     * Handle the editing of a menu, including updating menu data and optionally changing its image.
     *
     * @param object $user The user object representing the logged-in business, updating the menu data.
     * @param string $menuID The ID of the menu being edited.
     * @param array $requestData The request data containing updated menu details.
     * @param \CodeIgniter\HTTP\Files\UploadedFile $menuImage The uploaded menu image (can be null if no image uploaded).
     * @throws InvalidRequestException If validation of menu data fails during update process.
     * @throws ObjectNotFoundException If menu matching the ID does not exist.
     * @throws NotAuthorizedException If the user affiliated business does not own the menu.
     */
    public static function handleEditMenu($user, $menuID, $requestData, $menuImage)
    {
        // Start a transaction for database operations
        $db = \Config\Database::connect();
        $db->transStart();

        // Trim whitespace from all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Validate the menu data for update
        self::validateMenuData($requestData);
        
        // Retrieve the menu to be updated by its ID or throw an exception if not found
        $menu = MenuRepository::getMenuByIDOrThrowException($menuID);
        
        // Validate if the user owns the menu
        self::validateMenuOwnership($user->business_id, $menu);
        
        // Transform the menu data to match the database structure
        $transformedMenuData = self::transformMenuData($requestData);
        
        // Update the menu details in the database
        MenuRepository::updateMenu($menuID, $transformedMenuData);

        // Check if a new menu image has been uploaded
        if ($menuImage->isValid()) {
            // Validate the uploaded menu image
            self::validateImageFile($menuImage);
            
            // Remove any existing image file associated with the menu
            self::removeImageFileOfMenu($menu);
            
            // Save the new image file for the menu
            self::saveImageFile($user->business_id, $menuID, $menuImage);
        }
        
        // Complete the database transaction
        $db->transComplete();
    }

    /**
     * Handle the deletion of a menu by a business.
     *
     * @param object $user The user object representing the logged-in business, deleting the menu.
     * @param array $requestData The request data containing the ID of the menu to be deleted.
     * @throws ObjectNotFoundException If menu matching the ID does not exist.
     * @throws NotAuthorizedException If the user affiliated business does not own the menu.
     */
    public static function handleDeleteMenu($user, $requestData)
    {
        // Retrieve the menu to be deleted by its ID or throw an exception if not found
        $deletedMenu = MenuRepository::getMenuByIDOrThrowException($requestData['menu_item_id'] ?? '');
        
        // Validate if the user's affiliated business owns the menu
        self::validateMenuOwnership($user->business_id, $deletedMenu);
        
        // Remove any associated image file of the menu
        self::removeImageFileOfMenu($deletedMenu);
        
        // Delete the menu from the database
        MenuRepository::deleteMenu($deletedMenu->menu_item_id);
    }

    /**
     * Handle retrieving table data associated with a business.
     *
     * @param object $user The user object representing the logged-in business.
     * @param array $requestData The request data containing search and page parameters.
     * @return array An array containing business information, pagination details, and searched table number.
     */
    public static function handleGetBusinessTableData($user, $requestData)
    {
        // Retrieve the business information based on the user's business ID
        $userBusiness = BusinessRepository::getBusinessByID($user->business_id);

        // Calculate pagination details
        $currentPage = (int) ($requestData['page'] ?? 1);
        $totalPages = (int) ceil($userBusiness->num_of_tables / 10);

        // Determine the searched table number
        $searchedTableNumber = !array_key_exists('search', $requestData) || empty($requestData['search']) ?  NULL : (int) $requestData['search'];

        // Return the business information, pagination details, and searched table number
        return [
            'business' => $userBusiness,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'searched_table_number' => $searchedTableNumber
        ];
    }

    /**
     * Handle generating a QR code for a specific table.
     *
     * @param string $businessID The ID of the business.
     * @param int $tableNumber The number of the table.
     * @return string The rendered QR code image.
     */
    public static function handleGetTableQR($businessID, $tableNumber)
    {
        // Construct the URL for the table QR code
        $tableQRURL = base_url("customer/orders/menu/$businessID/$tableNumber");
        
        // Configure options for the QR code
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'scale' => 15,
            'outputBase64' => false,
        ]);
        
        // Generate the QR code using configured options
        $qrcode = new QRCode($options);
        return $qrcode->render($tableQRURL);
    }

    /**
     * Validate capacity data for registration.
     *
     * @param array $capacityData An associative array containing capacity data to be validated.
     * @throws InvalidRequestException When validation fails, this exception is thrown with the validation errors.
     */
    private static function validateCapacityData($capacityData)
    {
        // Define validation rules for capacity data
        $rules = [
            'new_table_quantity' => 'required|is_natural_no_zero'
        ];

        // Perform validation
        $validationResult = Validator::validate($rules, [], $capacityData);
        
        // Throw exception if validation fails
        if ($validationResult !== TRUE) {
            throw new InvalidRequestException($validationResult);
        }
    }

    /**
     * Handle the update of business table capacity.
     *
     * @param object $user The user object representing the logged-in business, updating its capacity.
     * @param array $requestData The request data containing updated table capacity details.
     * @throws InvalidRequestException If validation of capacity data fails during update process.
     */
    public static function handleUpdateBusinessTableCapacity($user, $requestData)
    {
        // Validate the table capacity data for update
        self::validateCapacityData($requestData);
        
        // Update the business table capacity
        BusinessRepository::updateBusiness(
            $user->business_id,
            [
                'num_of_tables' => $requestData['new_table_quantity']
            ]
        );
    }
}
