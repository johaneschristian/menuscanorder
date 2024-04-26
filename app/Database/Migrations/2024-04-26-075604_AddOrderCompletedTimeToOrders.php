<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOrderCompletedTimeToOrders extends Migration
{
    public function up()
    {
        $fields = [
            'order_completion_time' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ]
        ];

        $this->forge->addColumn('orders', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'order_completion_time');
    }
}
