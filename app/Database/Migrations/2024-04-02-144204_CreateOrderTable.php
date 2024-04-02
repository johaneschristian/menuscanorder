<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderTable extends Migration
{
    public function up()
    {
        $fields = [
            'order_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => FALSE,
            ],
            'order_creation_time' => [
                'type' => 'TIMESTAMP',
                'null' => FALSE,
            ],
            'order_status_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'table_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
            ],
            'submitting_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'receiving_business_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => FALSE,
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('order_id');
        $this->forge->addForeignKey('order_status_id', 'order_statuses', 'id', 'CASCADE', 'RESTRICT', 'order_order_status_id_fk');
        $this->forge->addForeignKey('submitting_user_id', 'users', 'id', 'CASCADE', 'RESTRICT', 'order_submitting_user_id_fk');
        $this->forge->addForeignKey('receiving_business_id', 'businesses', 'business_id', 'CASCADE', 'RESTRICT', 'order_receiving_business_id_fk');
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
