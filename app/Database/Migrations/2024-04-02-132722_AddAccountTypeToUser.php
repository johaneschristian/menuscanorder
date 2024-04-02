<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAccountTypeToUser extends Migration
{
    public function up()
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

    public function down()
    {
        // $this->forge->dropForeignKey('users', 'account_type_id');
        $this->forge->dropColumn('users', 'account_type_id');
    }
}
