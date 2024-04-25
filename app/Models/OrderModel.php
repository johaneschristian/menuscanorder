<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'order_id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'order_id',
        'order_creation_time',
        'order_completion_time',
        'order_status_id',
        'table_number',
        'submitting_user_id',
        'receiving_business_id'
    ];
}
