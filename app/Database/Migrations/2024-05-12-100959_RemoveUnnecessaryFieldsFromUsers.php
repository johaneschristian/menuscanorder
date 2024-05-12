<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveUnnecessaryFieldsFromUsers extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('users', 'account_type_id');
    }

    public function down()
    {
        $fields = [
            'account_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ]
        ];
        $this->forge->addColumn('users', $fields);
        $this->forge->addForeignKey('account_type_id', 'account_types', 'id', 'CASCADE', 'RESTRICT','account_type_id_fk');
    }
}
