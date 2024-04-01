<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

class AddNameAndArchivedToUsers extends Migration
{
    /**
     * @var string[]
     */
    private array $tables;

    public function __construct(?Forge $forge = null)
    {
        parent::__construct($forge);

        /** @var \Config\Auth $authConfig */
        $authConfig   = config('Auth');
        $this->tables = $authConfig->tables;
    }

    public function up()
    {
        $fields = [
            'name' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => false, 'after' => 'username'],
            'is_archived' => ['type' => 'BOOL', 'default' => false, 'after' => 'username'],
        ];
        $this->forge->addColumn($this->tables['users'], $fields);
    }

    public function down()
    {
        $fields = [
            'name',
            'is_archived'
        ];
        $this->forge->dropColumn($this->tables['users'], $fields);
    }
}