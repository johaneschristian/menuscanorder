<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyItemOrderTimeOnOrderItemModelToStopUpdatingAutomatically extends Migration
{
    public function up()
    {
        $fields = [
            'item_order_time' => [
                'type' => 'DATETIME',
                'null' => FALSE,
            ]
        ];

        $this->forge->modifyColumn('order_items', $fields);
    }

    public function down()
    {
        $fields = [
            'item_order_time' => [
                'type' => 'TIMESTAMP',
                'null' => FALSE,
            ]
        ];

        $this->forge->modifyColumn('order_items', $fields);
    }
}
