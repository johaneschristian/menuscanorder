<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyAllTableToUseDouble extends Migration
{
    public function up()
    {
        $menuTableFields = [
            'price' => [
                'type' => 'DOUBLE',
                'default' => 0,
                'null' => FALSE,
            ]
        ];

        $orderTableFields = [
            'total_price' => [
                'type' => 'DOUBLE',
                'default' => 0,
            ] 
        ];

        $orderItemTableFields = [
            'price_when_bought' => [
                'type' => 'DOUBLE',
                'default' => 0
            ]
        ];

        $this->forge->modifyColumn('menu_items', $menuTableFields);
        $this->forge->modifyColumn('orders', $orderTableFields);
        $this->forge->modifyColumn('order_items', $orderItemTableFields);
    }

    public function down()
    {
        $menuTableFields = [
            'price' => [
                'type' => 'FLOAT',
                'default' => 0,
                'null' => FALSE,
            ]
        ];

        $orderTableFields = [
            'total_price' => [
                'type' => 'FLOAT',
                'default' => 0,
            ] 
        ];

        $orderItemTableFields = [
            'price_when_bought' => [
                'type' => 'FLOAT',
                'default' => 0
            ]
        ];

        $this->forge->modifyColumn('menu_items', $menuTableFields);
        $this->forge->modifyColumn('orders', $orderTableFields);
        $this->forge->modifyColumn('order_items', $orderItemTableFields);
    }
}
