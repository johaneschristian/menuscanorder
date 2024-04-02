<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemTable extends Migration
{
    public function up()
    {
        $fields = [
            'order_item_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => FALSE,
            ],
            'num_of_items' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'subtotal' => [
                'type' => 'FLOAT',
                'default' => 0,
                'null' => FALSE,
            ],
            'item_order_time' => [
                'type' => 'TIMESTAMP',
                'null' => FALSE,
            ],
            'order_item_status_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE,
            ],
            'notes' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'order_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => FALSE,
            ],
            'menu_item_id' => [
              'type' => 'VARCHAR',
              'constraint' => 36,
              'null' => TRUE,  
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('order_item_id');
        $this->forge->addForeignKey('order_item_status_id', 'order_item_statuses', 'id', 'CASCADE', 'RESTRICT', 'orderitem_order_item_status_id_fk');
        $this->forge->addForeignKey('order_id', 'orders', 'order_id', 'CASCADE', 'CACADE', 'orderitem_order_id_fk');
        $this->forge->addForeignKey('menu_item_id', 'menu_items', 'menu_item_id', 'CASCADE', 'SET NULL', 'orderitem_menu_item_id_fk');
        $this->forge->createTable('order_items');
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
    }
}
