<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'order_item_id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'order_item_id',
        'num_of_items',
        'price_when_bought',
        'item_order_time',
        'order_item_status_id',
        'notes',
        'order_id',
        'menu_item_id',
    ];
}
