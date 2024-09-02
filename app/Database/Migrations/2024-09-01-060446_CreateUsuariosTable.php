<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'apellido' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'correo' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'telefono' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'direccion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'clave' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'id_rol' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'id_empresa' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'es_superadmin' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_rol', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_empresa', 'empresas', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}