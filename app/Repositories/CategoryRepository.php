<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\CategoryModel;
use App\Utils\Utils;

/**
 * Repository to deal with database insertion, retrieval, and update for category model
 */
class CategoryRepository
{
    /**
     * Create a new category record in the database.
     *
     * @param string $owningBusinessID The ID of the business owning the category.
     * @param array $categoryData The data for the new category.
     * @return string ID of the created category
     */
    public static function createCategory($owningBusinessID, $categoryData)
    {
        $category = new CategoryModel();

        // Generate a new UUID for the category ID
        $categoryID = Utils::generateUUID();

        // Insert the new category data into the database
        $category->insert([
            'category_id' => $categoryID,
            'owning_business_id' => $owningBusinessID,
            'name' => $categoryData['name'],
        ]);

        return $categoryID;
    }

    /**
     * Update an existing category record in the database.
     *
     * @param string $updatedCategoryID The ID of the category to update.
     * @param array $updatedCategoryData The updated data for the category.
     * @return void
     */
    public static function updateCategory($updatedCategoryID, $updatedCategoryData)
    {
        $category = new CategoryModel();
        $category->update($updatedCategoryID, $updatedCategoryData);
    }

    /**
     * Delete a category record from the database.
     *
     * @param string $deletedCategoryID The ID of the category to delete.
     * @return void
     */
    public static function deleteCategory($deletedCategoryID)
    {
        $category = new CategoryModel();
        $category->delete($deletedCategoryID);
    }

    /**
     * Get the query for retrieving categories of a business from the database.
     *
     * @param string $owningBusinessID The ID of the business owning the categories.
     * @param string $categoryNameSearch The name pattern to search for in category names.
     * @param bool $withMenuCount Whether to include the count of associated menu items.
     * @return object The query for retrieving categories.
     */
    public static function getQueryOfCategoriesOfBusiness($owningBusinessID, $categoryNameSearch, $withMenuCount)
    {
        $query = new CategoryModel();

        // Modify query to include menu count if requested
        if ($withMenuCount) {
            $query = $query->select('menu_item_categories.category_id, menu_item_categories.name, COUNT(menu_item_id) AS menu_count')
                           ->join('menu_items', 'menu_items.category_id=menu_item_categories.category_id', 'left')
                           ->groupBy('menu_item_categories.category_id, menu_item_categories.name');
        }

        // Add conditions to the query based on owning business ID and category name search
        $query = $query->where('menu_item_categories.owning_business_id', $owningBusinessID)
                       ->like('menu_item_categories.name', $categoryNameSearch, 'both');

        return $query;
    }

    /**
     * Retrieve categories of a business from the database.
     *
     * @param string $owningBusinessID The ID of the business owning the categories.
     * @param string $categoryNameSearch The name pattern to search for in category names.
     * @param bool $withMenuCount Whether to include the count of associated menu items.
     * @return array An array of category records.
     */
    public static function getCategoriesOfBusiness($owningBusinessID, $categoryNameSearch, $withMenuCount = FALSE)
    {
        // Retrieve the query for retrieving categories of the business
        $query = self::getQueryOfCategoriesOfBusiness($owningBusinessID, $categoryNameSearch, $withMenuCount);

        // Execute the query and retrieve all category records
        return $query->findAll();
    }

    /**
     * Retrieve categories of a business from the database with pagination.
     *
     * @param string $owningBusinessID The ID of the business owning the categories.
     * @param string $categoryNameSearch The name pattern to search for in category names.
     * @param bool $withMenuCount Whether to include the count of associated menu items.
     * @param int $perPage Number of items per page.
     * @param int $currentPage Current page number.
     * @return array An array of paginated category records.
     */
    public static function getPaginatedCategoriesOfBusiness($owningBusinessID, $categoryNameSearch, $withMenuCount = FALSE, $perPage = 10, $currentPage = 1)
    {
        // Retrieve the query for retrieving categories of the business
        $query = self::getQueryOfCategoriesOfBusiness($owningBusinessID, $categoryNameSearch, $withMenuCount);

        // Paginate the query results
        return Utils::paginate($query, $perPage, $currentPage);
    }

    /**
     * Retrieve a category record from the database by its ID.
     *
     * @param string $categoryID The ID of the category to retrieve.
     * @return mixed The category record if found, otherwise NULL.
     */
    public static function getCategoryByID($categoryID)
    {
        $category = new CategoryModel();
        return $category->where('category_id', $categoryID)->first();
    }

    /**
     * Retrieve a category record from the database by its ID or throw an exception if not found.
     *
     * @param string $categoryID The ID of the category to retrieve.
     * @return object The category record if found.
     * @throws ObjectNotFoundException If the category with the specified ID does not exist.
     */
    public static function getCategoryByIDOrThrowException($categoryID)
    {
        // Retrieve the category record by its ID
        $matchingCategory = self::getCategoryByID($categoryID);

        // If the category is not found, throw an exception
        if (is_null($matchingCategory)) {
            throw new ObjectNotFoundException("Category with ID $categoryID does not exist");
            
        } else {
            return $matchingCategory;
        }
    }
}
