<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccountTypeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('account_types');
    }

    public function down()
    {
        $this->forge->dropTable('account_types');
    }
}
