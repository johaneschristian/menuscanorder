<?php

namespace App\Repositories;

use App\Models\CategoryModel;
use App\Utils\Utils;


class CategoryRepository
{
    public static function createCategory($owningBusiness, $categoryData) {
        $categories = new CategoryModel();
        $categories->insert([
            'category_id' => Utils::generateUUID(),
            'owning_business_id' => $owningBusiness['business_id'],
            'name' => $categoryData['category_name'],
        ]);
    }
}