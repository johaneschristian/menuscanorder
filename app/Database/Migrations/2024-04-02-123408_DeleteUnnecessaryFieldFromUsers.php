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
        // $fields = [
        //     'status',
        //     'status_message',
        //     'active',
        //     'last_active',
        //     'created_at',
        //     'updated_at',
        //     'deleted_at',
        // ];
        // $this->forge->dropColumn($this->tables['users'], $fields);
    }

    public function down()
    {
        // $fields = [
        //     'name',
        //     'is_archived'
        // ];
        // $this->forge->dropColumn($this->tables['users'], $fields);
    }
}