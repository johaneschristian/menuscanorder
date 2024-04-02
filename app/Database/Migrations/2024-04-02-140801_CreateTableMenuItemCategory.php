<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMenuItemCategory extends Migration
{
    public function up()
    {
        $fields = [
            'category_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => FALSE,
            ],
            'owning_business_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => FALSE,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('category_id');
        $this->forge->addForeignKey('owning_business_id', 'businesses', 'business_id', 'CASCADE', 'CASCADE', 'menuitemcategory_owning_business_id_category_fk');
        $this->forge->createTable('menu_item_categories');
    }

    public function down()
    {
        $this->forge->dropTable('menu_item_categories');
    }
}
