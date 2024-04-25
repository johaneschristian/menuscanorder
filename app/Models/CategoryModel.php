<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'menu_item_categories';
    protected $primaryKey       = 'category_id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'category_id',
        'owning_business_id',
        'name',
    ];
}
