<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientesTable extends Migration
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
            'identidad' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'num_identidad' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'apellido' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'correo' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'direccion' => [
                'type'       => 'TEXT',
            ],
            'habido' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'razon' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'referencia' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'imgperfil' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'nombre_negocio' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'direccion_negocio' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tipo_negocio' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('clientes');
    }

    public function down()
    {
        $this->forge->dropTable('clientes');
    }
}
