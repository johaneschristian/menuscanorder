<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    public function run()
    {
        $order_status_data = [
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
                'status' => 'completed',
            ],
        ];

        foreach ($order_status_data as $order_status_datum) {
            $this->db->table('order_statuses')->insert($order_status_datum);
        }
    }
}
