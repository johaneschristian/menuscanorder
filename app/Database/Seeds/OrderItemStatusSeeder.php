<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrderItemStatusSeeder extends Seeder
{
    public function run()
    {
        $order_item_status_data = [
            [
                'id' => 1,
                'status' => 'received',
            ],
            [
                'id' => 2,
                'status' => 'being prepared',
            ],
            [
                'id' => 3,
                'status' => 'served',
            ],
        ];
        
        foreach ($order_item_status_data as $order_item_status_datum) {
            $this->db->table('order_item_statuses')->insert($order_item_status_datum);
        }
    }
}
