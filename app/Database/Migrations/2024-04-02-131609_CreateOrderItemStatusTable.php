<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemStatusTable extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ]
        ];
        
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('order_item_statuses');
    }

    public function down()
    {
        $this->forge->dropTable('order_item_statuses');
    }
}
