<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBusinessTable extends Migration
{
    public function up()
    {
        $fields = [
            'business_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'null' => FALSE,
            ],
            'owning_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ],
            'business_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
            'num_of_tables' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
            ],
            'is_open' => [
                'type' => 'BOOL',
                'default' => TRUE,
            ],
            'business_is_archived' => [
                'type' => 'BOOL',
                'default' => TRUE,
            ]
        ];

        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('business_id');
        $this->forge->addForeignKey('owning_user_id', 'users', 'id', 'CASCADE', 'CASCADE', 'owning_user_id_fk');
        $this->forge->createTable('businesses');
    }

    public function down()
    {
        $this->forge->dropTable('businesses');
    }
}
