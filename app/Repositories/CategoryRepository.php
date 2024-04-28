<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\CategoryModel;
use App\Utils\Utils;


class CategoryRepository
{
    // TODO: Clean, simplify
    public static function createCategory($owningBusinessID, $categoryData) {
        $category = new CategoryModel();
        return $category->insert([
            'category_id' => Utils::generateUUID(),
            'owning_business_id' => $owningBusinessID,
            'name' => $categoryData['category_name'],
        ], TRUE);
    }

    public static function updateCategory($updatedCategory, $categoryData) {
        // TODO: Change format
        $category = new CategoryModel();
        $updatedCategory->name = $categoryData['category_name'];
        $category->save($updatedCategory);
    }

    public static function getCategoriesOfBusiness($owningBusinessID, $categoryNameSearch) {
        $category = new CategoryModel();
        $businessCategories = $category
                                ->where('owning_business_id', $owningBusinessID)
                                ->like('name', $categoryNameSearch, 'both')
                                ->findAll();
        
        foreach($businessCategories as $category) {
            $category->menu_count = 0;
        }

        return $businessCategories;
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