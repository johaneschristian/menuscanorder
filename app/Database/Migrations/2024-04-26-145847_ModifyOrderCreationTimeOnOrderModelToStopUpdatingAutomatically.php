<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyOrderCreationTimeOnOrderModelToStopUpdatingAutomatically extends Migration
{
    public function up()
    {
        $fields = [
            'order_creation_time' => [
                'type' => 'DATETIME',
                'null' => FALSE,
            ]
        ];

        $this->forge->modifyColumn('orders', $fields);
    }

    public function down()
    {
        $fields = [
            'order_creation_time' => [
                'type' => 'TIMESTAMP',
                'null' => FALSE,
            ]
        ];

        $this->forge->modifyColumn('orders', $fields);
    }
}
