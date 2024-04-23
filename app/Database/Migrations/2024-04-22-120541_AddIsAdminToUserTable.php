<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsAdminToUserTable extends Migration
{
    public function up()
    {
        $fields = [
            'is_admin' => [
                'type' => 'BOOL',
                'default' => FALSE,
            ]
        ];

        $this->forge->addColumn('users', $fields);  
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'is_admin');
    }
}
