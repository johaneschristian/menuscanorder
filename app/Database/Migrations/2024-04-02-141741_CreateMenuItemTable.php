<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMenuItemTable extends Migration
{
    public function up()
    {
        $fields = [
            'menu_item_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => FALSE,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'image_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'price' => [
                'type' => 'FLOAT',
                'default' => 0,
                'null' => FALSE,
            ],
            'is_available' => [
                'type' => 'BOOL',
                'default' => TRUE,
                'null' => FALSE,
            ],
            'category_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => TRUE,
            ],
            'owning_business_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => FALSE
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('menu_item_id');
        $this->forge->addForeignKey('category_id', 'menu_item_categories', 'category_id', 'CASCADE', 'SET NULL', 'menuitem_category_id_fk');
        $this->forge->addForeignKey('owning_business_id', 'businesses', 'business_id', 'CASCADE', 'CASCADE', 'menuitem_owning_business_id_fk');
        $this->forge->createTable('menu_items');
    }

    public function down()
    {
        $this->forge->dropTable('menu_items');
    }
}
