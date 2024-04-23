<?php

namespace App\Models;

use CodeIgniter\Model;

class BusinessModel extends Model
{
    protected $table            = 'businesses';
    protected $primaryKey       = 'business_id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'business_id',
        'owning_user_id',
        'business_name',
        'num_of_tables',
        'address',
        'is_open',
        'business_is_archived'
    ];
}
