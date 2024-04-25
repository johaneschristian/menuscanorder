<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\CategoryModel;
use App\Utils\Utils;


class CategoryRepository
{
    public static function createCategory($owningBusiness, $categoryData) {
        $category = new CategoryModel();
        return $category->insert([
            'category_id' => Utils::generateUUID(),
            'owning_business_id' => $owningBusiness->business_id,
            'name' => $categoryData['category_name'],
        ], TRUE);
    }

    public static function updateCategory($updatedCategory, $categoryData) {
        $category = new CategoryModel();
        $updatedCategory->name= $categoryData['category_name'];
        $category->save($updatedCategory);
    }

    public static function getCategoriesOfBusiness($owningBusiness, $categoryNameSearch) {
        $category = new CategoryModel();
        $businessCategories = $category
                                ->where('owning_business_id', $owningBusiness->business_id)
                                ->like('name', $categoryNameSearch, 'both')
                                ->findAll();
        
        $businessCategoriesWithMenuCount = [];
        foreach($businessCategories as $category) {
            $businessCategoriesWithMenuCount[] = [
                ...(array) $category, 
                'menu_count' => 0
            ];
        }

        return $businessCategoriesWithMenuCount;
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