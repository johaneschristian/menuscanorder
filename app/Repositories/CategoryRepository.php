<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\CategoryModel;
use App\Utils\Utils;


class CategoryRepository
{
    public static function createCategory($owningBusinessID, $categoryData) {
        $category = new CategoryModel();
        $category->insert([
            'category_id' => Utils::generateUUID(),
            'owning_business_id' => $owningBusinessID,
            'name' => $categoryData['name'],
        ]);
    }

    public static function updateCategory($updatedCategoryID, $updatedCategoryData) {
        $category = new CategoryModel();
        $category->update($updatedCategoryID, $updatedCategoryData);
    }

    public static function deleteCategory($deletedCategoryID) {
        $category = new CategoryModel();
        $category->delete($deletedCategoryID);
    }

    public static function getQueryOfCategoriesOfBusiness($owningBusinessID, $categoryNameSearch, $withMenuCount) {
        $query = new CategoryModel();

        if ($withMenuCount) {
            $query = $query
                        ->select('menu_item_categories.category_id, menu_item_categories.name, COUNT(menu_item_id) AS menu_count')
                        ->join('menu_items', 'menu_items.category_id=menu_item_categories.category_id')
                        ->groupBy('menu_item_categories.category_id, menu_item_categories.name');
        }

        $query = $query->where('menu_item_categories.owning_business_id', $owningBusinessID)->like('menu_item_categories.name', $categoryNameSearch, 'both');        
        return $query;
    }

    public static function getCategoriesOfBusiness($owningBusinessID, $categoryNameSearch, $withMenuCount = FALSE) {     
        return self::getQueryOfCategoriesOfBusiness($owningBusinessID, $categoryNameSearch, $withMenuCount)->findAll();
    }
    
    public static function getPaginatedCategoriesOfBusiness($owningBusinessID, $categoryNameSearch, $perPage = 10, $currentPage = 1, $withMenuCount = FALSE) {
        $query = self::getQueryOfCategoriesOfBusiness($owningBusinessID, $categoryNameSearch, $withMenuCount);
        return Utils::paginate($query, $perPage, $currentPage);
    }

    public static function getCategoryByID($categoryID) {
        $category = new CategoryModel();
        $matchingCategory = $category->where('category_id', $categoryID)->first();
        return $matchingCategory;
    }

    public static function getCategoryByIDOrThrowException($categoryID) {
        $matchingCategory = self::getCategoryByID($categoryID);

        if (is_null($matchingCategory)) {
            throw new ObjectNotFoundException("Category with ID $categoryID does not exist");
        } else {
            return $matchingCategory;
        }
    }
}