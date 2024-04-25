<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTotalPriceFieldToOrdersTable extends Migration
{
    public function up()
    {
        $fields = [
            'total_price' => [
                'type' => 'FLOAT',
                'default' => 0,
            ] 
        ];

        $this->forge->addColumn('orders', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'total_price');
    }
}
