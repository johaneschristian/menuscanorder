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
    public static function handleGetBusinessProfile($user) 
    {
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
    public static function userHasBusiness($user) 
    {
        $associatedBusiness = BusinessRepository::getBusinessByUserID($user->id);
        return !is_null($associatedBusiness);
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
     * Handle the registration or update of a business.
     *
     * @param object $user The user object representing the logged-in user, initiating the business registration or update.
     * @param array $requestData The request data containing business details.
     * @throws InvalidRequestException If business details is invalid.
     */
    public static function handleCreateOrEditBusiness($user, $requestData)
    {
        // Trim all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Validate the trimmed business data
        self::validateBusinessData($requestData);

        // Transform the validated business data
        $transformedBusinessData = self::transformBusinessData($requestData);

        if (!isset($user->business_id)) {
            // Create a new business record in the database
            BusinessRepository::createBusiness($user->id, $transformedBusinessData);

        } else {
            // Update the business record in the database
            BusinessRepository::updateBusiness($user->business_id, $transformedBusinessData);
        }
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
     * Handle business' create or update of a category by a business.
     *
     * @param object $user The user object representing the logged-in business, creating/updating the category.
     * @param array $requestData The request data containing category details.
     * @throws NotAuthorizedException If the user affiliated business does not own the category (during an update).
     * @throws InvalidRequestException If validation of category data fails during the process.
     * @throws ObjectNotFoundException If category matching ID does not exist (during an update).
     */
    public static function handleCreateOrEditCategory($user, $requestData) {
        $isCreate = !array_key_exists('category_id', $requestData);

        // Trim whitespace from all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Validate the category data for update
        self::validateCategoryData($requestData, $isCreate);

        if ($isCreate) {
            // Create a new category record in the database
            CategoryRepository::createCategory($user->business_id, $requestData);

        } else {
            // Validate if the user owns the category
            $updatedCategory = CategoryRepository::getCategoryByIDOrThrowException($requestData['category_id']);
            self::validateCategoryOwnership($user->business_id, $updatedCategory);

            // Update the category details
            CategoryRepository::updateCategory($updatedCategory->category_id, $requestData);
        }
    }

    /**
     * Handle the retrieval all categories associated with a user's business.
     *
     * @param object $user The user object representing the logged-in business.
     * @param array $requestData The request data containing search and pagination parameters.
     * @return array An array containing paginated categories and search parameter.
     */
    public static function handleGetCategoryList($user, $requestData)
    {
        // Retrieve paginated categories associated with the user's business
        $businessCategories = CategoryRepository::getCategoriesOfBusiness(
            $user->business_id,
            $requestData['search'] ?? '',
            FALSE,
        );

        // Return paginated categories, search parameter, and pager information
        return [
            'categories' => $businessCategories,
            'search' => $requestData['search'] ?? '',
        ];
    }

    /**
     * Handle the retrieval of a paginated list of categories associated with a user's business.
     *
     * @param object $user The user object representing the logged-in business.
     * @param array $requestData The request data containing search and pagination parameters.
     * @return array An array containing paginated categories, search parameter, and pager information.
     */
    public static function handleGetPaginatedCategoryList($user, $requestData)
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
     * Handle the deletion of a category by a business.
     *
     * @param object $user The user object representing the logged-in business, deleting the category.
     * @param array $requestData The request data containing the ID of the category to be deleted.
     * @throws NotAuthorizedException If the user does not own the category.
     * @throws ObjectNotFoundException If category matching ID does not exist.
     */
    public static function handleDeleteCategory($user, $requestData) 
    {
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

        // Include category ID or if 'others', set to NULL
        if (array_key_exists('category_id', $menuData) && $menuData['category_id'] !== 'others') {
            $dataToBeUpdated['category_id'] = $menuData['category_id'] === 'others' ? NULL : $menuData['category_id'];
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
        try {
            // Check if the menu has an associated image
            if (!is_null($menu->image_url)) {
                // Construct the path to the image file
                $filePath = WRITEPATH . 'menu_images/' . $menu->image_url;

                // Delete the image file from the server
                unlink($filePath);
            }

        } catch (\Exception $exception) {
            // Prevent error if file has been deleted manually
        }
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
            throw new NotAuthorizedException("Menu with ID {$menu->menu_item_id} does not belong to the business with ID $businessID");
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
     * Updates the image associated with a menu entry if image exists.
     *
     * @param int $menuID The ID of the menu entry to update the image for.
     * @param \CodeIgniter\HTTP\Files\UploadedFile $menuImage The uploaded image file.
     */
    private static function updateMenuImage($menuID, $menuImage) 
    {
        // Check if the uploaded image is valid and has not been moved
        if ($menuImage->isValid() && !$menuImage->hasMoved()) {
            // Retrieve the menu object from the database using its ID
            $menu = MenuRepository::getMenuByIDOrThrowException($menuID);

            // Validate the uploaded image file
            self::validateImageFile($menuImage);

            // Remove any existing image file associated with the menu if a new image is uploaded
            self::removeImageFileOfMenu($menu);
            
            // Save the uploaded image file to the appropriate directory
            // using the owning business ID and the menu ID
            self::saveImageFile($menu->owning_business_id, $menuID, $menuImage);
        }
    }

    /**
     * Handle the creation or editing of a menu and assigning an image to the menu.
     *
     * @param object $user The user object representing the logged-in business, creating/updating the menu data.
     * @param string|null $menuID The ID of the menu being edited, null when it is create.
     * @param array $requestData The request data containing the menu details.
     * @param \CodeIgniter\HTTP\Files\UploadedFile $menuImage The uploaded menu image.
     * @throws InvalidRequestException If validation of menu data fails during update/create process.
     * @throws ObjectNotFoundException If menu matching the ID does not exist (during update).
     * @throws NotAuthorizedException If the user affiliated business does not own the menu (during update).
     */
    public static function handleCreateOrEditMenu($user, $menuID, $requestData, $menuImage)
    {
        // Start a transaction for database operations to prevent commit before image is saved
        $db = \Config\Database::connect();
        $db->transStart();

        $isCreate = is_null($menuID);

        // Trim whitespace from all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Validate the menu data for update
        self::validateMenuData($requestData);

        // Transform the menu data to match the database structure
        $transformedMenuData = self::transformMenuData($requestData);
        
        if ($isCreate) {
            // Create the menu entry in the database
            $menuID = MenuRepository::createMenu($user->business_id, $transformedMenuData);

        } else {
            // Perform additional validation if operation is update
            $menu = MenuRepository::getMenuByIDOrThrowException($menuID);
            self::validateMenuOwnership($user->business_id, $menu);

            // Update the menu details in the database
            MenuRepository::updateMenu($menuID, $transformedMenuData);
        }

        // Save the uploaded image file
        self::updateMenuImage($menuID, $menuImage);
        
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
