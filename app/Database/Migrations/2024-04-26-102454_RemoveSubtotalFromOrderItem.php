<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveSubtotalFromOrderItem extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('order_items', 'subtotal');
    }

    public function down()
    {
        $fields = [
            'subtotal' => [
                'type' => 'FLOAT',
                'default' => 0,
            ],
        ];

        $this->forge->addColumn('order_items', $fields);
    }
}
