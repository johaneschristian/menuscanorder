<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\CategoryModel;
use App\Models\MenuItemModel;
use App\Utils\Utils;

class MenuRepository 
{
    public static function createMenu($owningBusinessID, $menuData) {
        $menu = new MenuItemModel();
        $menuItemID = Utils::generateUUID();
        $dataToBeInserted = [
            'menu_item_id' => $menuItemID,
            'owning_business_id' => $owningBusinessID,
            ...$menuData,
        ];

        $menu->insert($dataToBeInserted);
        return $menuItemID;
    }

    public static function updateMenu($menuItemID, $menuData) {
        $menu = new MenuItemModel();
        $menu->update($menuItemID, $menuData);
    }

    public static function updateMenuImage($menuItemID, $imageURL) {
        $menu = new MenuItemModel();
        $menu->update($menuItemID, [
            'image_url' => $imageURL,
        ]);
    }

    public static function getMenuByID($menuItemID) {
        $menu = new MenuItemModel();
        return $menu->where('menu_item_id', $menuItemID)->first();
    }

    public static function getMenuByIDOrThrowException($menuItemID) {
        $foundMenu = self::getMenuByID($menuItemID);

        if (is_null($foundMenu)) {
            throw new ObjectNotFoundException("Menu with ID $menuItemID does not exist.");
        
        } else {
            return $foundMenu;
        }
    }

    public static function getMenuItemsOfBusiness($businessID) {
        $menu = new MenuItemModel();
        return $menu->where('owning_business_id', $businessID)->findAll();
    }

    private static function getQueryOfMenuItemsOfBusinessMatchingNameAndCategory($businessID, $name, $categoryID, $mustBeAvailable) {
        $menu = new MenuItemModel();
        $query = $menu->where('owning_business_id', $businessID)->like('name', $name, 'both', TRUE);

        if ($mustBeAvailable) {
            $query = $query->where('is_available', TRUE);
        }

        if ($categoryID !== "all") {
            $query = $query->where('category_id', $categoryID);
        }

        return $query;
    }

    public static function getMenuItemsOfBusinessMatchingNameAndCategory($businessID, $name, $categoryID, $mustBeAvailable = FALSE) {
        return self::getQueryOfMenuItemsOfBusinessMatchingNameAndCategory(
            $businessID, 
            $name, 
            $categoryID, 
            $mustBeAvailable
        )->findAll();
    }

    public static function getPaginatedMenuItemsOfBusinessMatchingNameAndCategory($businessID, $name, $categoryID, $mustBeAvailable = FALSE, $perPage = 10, $currentPage = 1) {
        $query = self::getQueryOfMenuItemsOfBusinessMatchingNameAndCategory(
            $businessID, 
            $name, 
            $categoryID, 
            $mustBeAvailable
        );

        return Utils::paginate($query, $perPage, $currentPage);
    }

    public static function getMenuItemsOfBusinessGroupByCategory($businessID, $mustBeAvailable = FALSE) {
        $category = new CategoryModel();
        $allCategories = $category->where('owning_business_id', $businessID)->findAll();

        $allCategoriesWithMenus = [];
        foreach($allCategories as $category) {
            $allCategoriesWithMenus[] = [
                ...(array) $category,
                'menus' => self::getMenuItemsOfBusinessMatchingNameAndCategory($businessID, "", $category->category_id, $mustBeAvailable)
            ];
        }

        $allCategoriesWithMenus[] = [
            'category_id' => NULL,
            'name' => 'Others',
            'menus' => self::getMenuItemsOfBusinessMatchingNameAndCategory($businessID, "", NULL, $mustBeAvailable), 
        ];

        return $allCategoriesWithMenus;
    }
}