<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePermisosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'modulo' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'campos' => [
                'type'       => 'TEXT',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('permisos');
    }

    public function down()
    {
        $this->forge->dropTable('permisos');
    }
}
