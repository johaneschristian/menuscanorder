<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\CategoryModel;
use App\Models\MenuItemModel;
use App\Utils\Utils;

/**
 * Repository to deal with database insertion, retrieval, and update for menu-item model
 */
class MenuRepository
{
    /**
     * Create a new menu item in the database.
     *
     * @param string $owningBusinessID The ID of the business owning the menu item.
     * @param array $menuData The data for the new menu item.
     * @return string The ID of the newly created menu item.
     */
    public static function createMenu($owningBusinessID, $menuData)
    {
        $menu = new MenuItemModel();
        
        // Generate a new UUID for the menu item ID
        $menuItemID = Utils::generateUUID();
        
        // Prepare the data to be inserted
        $dataToBeInserted = [
            'menu_item_id' => $menuItemID,
            'owning_business_id' => $owningBusinessID,
            ...$menuData,
        ];

        // Insert the new menu item data into the database
        $menu->insert($dataToBeInserted);
        
        // Return the ID of the newly created menu item
        return $menuItemID;
    }

    /**
     * Update an existing menu item in the database.
     *
     * @param string $menuItemID The ID of the menu item to update.
     * @param array $menuData The updated data for the menu item.
     * @return void
     */
    public static function updateMenu($menuItemID, $menuData)
    {
        $menu = new MenuItemModel();
        $menu->update($menuItemID, $menuData);
    }

    /**
     * Delete a menu item from the database.
     *
     * @param string $menuItemID The ID of the menu item to delete.
     * @return void
     */
    public static function deleteMenu($menuItemID)
    {
        $menu = new MenuItemModel();
        $menu->delete($menuItemID);
    }

    /**
     * Retrieve a menu item from the database by its ID.
     *
     * @param string $menuItemID The ID of the menu item to retrieve.
     * @return mixed The menu item record if found, otherwise NULL.
     */
    public static function getMenuByID($menuItemID)
    {
        $menu = new MenuItemModel();
        return $menu->where('menu_item_id', $menuItemID)->first();
    }

    /**
     * Retrieve a menu item from the database by its ID or throw an exception if not found.
     *
     * @param string $menuItemID The ID of the menu item to retrieve.
     * @return object The menu item record if found.
     * @throws ObjectNotFoundException If the menu item with the specified ID does not exist.
     */
    public static function getMenuByIDOrThrowException($menuItemID)
    {
        // Retrieve the menu item by its ID
        $foundMenu = self::getMenuByID($menuItemID);

        // If the menu item is not found, throw an exception
        if (is_null($foundMenu)) {
            throw new ObjectNotFoundException("Menu with ID $menuItemID does not exist.");

        } else {
            return $foundMenu;
        }
    }

    /**
     * Retrieve menu items of a business from the database.
     *
     * @param string $businessID The ID of the business owning the menu items.
     * @return array An array of menu item records.
     */
    public static function getMenuItemsOfBusiness($businessID)
    {
        $menu = new MenuItemModel();
        return $menu->where('owning_business_id', $businessID)->findAll();
    }

    /**
     * Get the query for retrieving menu items of a business matching name and category from the database.
     *
     * @param string $businessID The ID of the business owning the menu items.
     * @param string $name The name pattern to search for in menu item names.
     * @param string $categoryID The ID of the category to filter menu items by.
     * @param bool $mustBeAvailable Whether menu items must be available.
     * @return object The query for retrieving menu items.
     */
    private static function getQueryOfMenuItemsOfBusinessMatchingNameAndCategory($businessID, $name, $categoryID, $mustBeAvailable)
    {
        // Create a new MenuItemModel instance
        $menu = new MenuItemModel();
        
        // Search for menu items of business with matching names
        $query = $menu->where('owning_business_id', $businessID)
                      ->like('name', $name, 'both', TRUE);

        // Add condition for availability if required
        if ($mustBeAvailable) {
            $query = $query->where('is_available', TRUE);
        }

        // Add condition for filtering by category if a specific category is provided
        if ($categoryID !== "all") {
            $query = $query->where('category_id', $categoryID);
        }

        // Return the constructed query
        return $query;
    }

    /**
     * Retrieve menu items of a business from the database matching name and category.
     *
     * @param string $businessID The ID of the business owning the menu items.
     * @param string $name The name pattern to search for in menu item names.
     * @param string $categoryID The ID of the category to filter menu items by.
     * @param bool $mustBeAvailable Whether menu items must be available.
     * @return array An array of menu item records.
     */
    public static function getMenuItemsOfBusinessMatchingNameAndCategory($businessID, $name, $categoryID, $mustBeAvailable = FALSE)
    {
        // Retrieve menu items matching name and category using the constructed query
        return self::getQueryOfMenuItemsOfBusinessMatchingNameAndCategory(
            $businessID,
            $name,
            $categoryID,
            $mustBeAvailable
        )->findAll();
    }

    /**
     * Retrieve paginated menu items of a business from the database matching name and category.
     *
     * @param string $businessID The ID of the business owning the menu items.
     * @param string $name The name pattern to search for in menu item names.
     * @param string $categoryID The ID of the category to filter menu items by.
     * @param bool $mustBeAvailable Whether menu items must be available.
     * @param int $perPage Number of items per page.
     * @param int $currentPage Current page number.
     * @return array An array of paginated menu item records.
     */
    public static function getPaginatedMenuItemsOfBusinessMatchingNameAndCategory($businessID, $name, $categoryID, $mustBeAvailable = FALSE, $perPage = 10, $currentPage = 1)
    {
        // Retrieve the query for retrieving menu items matching name and category
        $query = self::getQueryOfMenuItemsOfBusinessMatchingNameAndCategory(
            $businessID,
            $name,
            $categoryID,
            $mustBeAvailable
        );

        // Paginate the query results
        return Utils::paginate($query, $perPage, $currentPage);
    }

    /**
     * Retrieve menu items of a business from the database grouped by category.
     *
     * @param string $businessID The ID of the business owning the menu items.
     * @param bool $mustBeAvailable Whether menu items must be available.
     * @return array An array of menu item records grouped by category.
     */
    public static function getMenuItemsOfBusinessGroupByCategory($businessID, $mustBeAvailable = FALSE)
    {
        $category = new CategoryModel();
        
        // Retrieve all categories owned by the business
        $allCategories = $category->where('owning_business_id', $businessID)->findAll();

        // Initialize an array to store all categories with associated menus
        $allCategoriesWithMenus = [];

        // Fetch associated menus of each category
        foreach ($allCategories as $category) {
            $allCategoriesWithMenus[] = [
                ...(array) $category,
                'menus' => self::getMenuItemsOfBusinessMatchingNameAndCategory($businessID, "", $category->category_id, $mustBeAvailable)
            ];
        }

        // Retrieve menus not associated with any category (others)
        $allCategoriesWithMenus[] = [
            'category_id' => NULL,
            'name' => 'Others',
            'menus' => self::getMenuItemsOfBusinessMatchingNameAndCategory($businessID, "", NULL, $mustBeAvailable),
        ];

        // Return the array containing all categories with associated menus
        return $allCategoriesWithMenus;
    }
}
