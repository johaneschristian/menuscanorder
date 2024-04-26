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
                'status' => 'New Order',
            ],
            [
                'id' => 2,
                'status' => 'In-Progress',
            ],
            [
                'id' => 3,
                'status' => 'Completed',
            ],
        ];

        foreach ($order_status_data as $order_status_datum) {
            $this->db->table('order_statuses')->insert($order_status_datum);
        }
    }
}
