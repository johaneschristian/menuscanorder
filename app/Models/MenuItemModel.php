<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuItemModel extends Model
{
    protected $table            = 'menu_items';
    protected $primaryKey       = 'menu_item_id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'menu_item_id',
        'name',
        'description',
        'image_url',
        'price',
        'is_available',
        'category_id',
        'owning_business_id',
    ];
}
