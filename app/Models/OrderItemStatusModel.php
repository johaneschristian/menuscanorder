<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemStatusModel extends Model
{
    protected $table            = 'order_item_statuses';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'status'];
}
