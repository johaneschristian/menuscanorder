<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPriceWhenBoughtFieldToOrderItem extends Migration
{
    public function up()
    {
        $fields = [
            'price_when_bought' => [
                'type' => 'FLOAT',
                'default' => 0
            ]
        ];

        $this->forge->addColumn('order_items', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('order_items', 'price_when_bought');
    }
}
