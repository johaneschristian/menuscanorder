<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\CategoryModel;
use App\Models\MenuItemModel;
use App\Utils\Utils;

class MenuRepository 
{
    public static function createMenu($owningBusiness, $menuData) {
        $menu = new MenuItemModel();
        $menuItemID = Utils::generateUUID();
        $dataToBeInserted = [
            'menu_item_id' => $menuItemID,
            'owning_business_id' => $owningBusiness->business_id,
            ...$menuData,
        ];

        $menu->insert($dataToBeInserted);
        return $menuItemID;
    }

    public static function updateMenu($menuItemID, $menuData) {
        $menu = new MenuItemModel();
        $menu->where('menu_item_id', $menuItemID)
             ->set($menuData)
             ->update();
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

        if ($foundMenu === NULL) {
            throw new ObjectNotFoundException("Menu with ID $menuItemID does not exist.");
        
        } else {
            return $foundMenu;
        }
    }

    public static function getMenuItemsOfBusiness($businessID) {
        $menu = new MenuItemModel();
        return $menu->where('owning_business_id', $businessID)->findAll();
    }

    public static function getMenuItemsOfBusinessMatchingNameAndCategory($businessID, $name, $categoryID) {
        $menu = new MenuItemModel();
        $query = $menu->where('owning_business_id', $businessID)->like('name', $name, 'both', TRUE);

        if ($categoryID !== "all") {
            $query = $query->where('category_id', $categoryID);
        }
        
        return $query->findAll();
    }

    public static function getMenuItemsOfBusinessGroupByCategory($businessID) {
        $category = new CategoryModel();
        $allCategories = $category->findAll();

        $allCategoriesWithMenus = [];
        foreach($allCategories as $category) {
            $allCategoriesWithMenus[] = [
                ...(array) $category,
                'menus' => self::getMenuItemsOfBusinessMatchingNameAndCategory($businessID, "", $category->category_id)
            ];
        }

        $allCategoriesWithMenus[] = [
            'category_id' => NULL,
            'name' => 'Others',
            'menus' => self::getMenuItemsOfBusinessMatchingNameAndCategory($businessID, "", NULL), 
        ];

        return $allCategoriesWithMenus;
    }
}