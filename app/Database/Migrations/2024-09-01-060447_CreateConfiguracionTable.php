<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConfiguracionTable extends Migration
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
                'constraint' => '20',
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'direccion' => [
                'type'       => 'TEXT',
            ],
            'correo' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'mensaje' => [
                'type'       => 'TEXT',
            ],
            'tasa_interes' => [
                'type'       => 'INT',
            ],
            'cuotas' => [
                'type'       => 'INT',
            ],
            'mensaje_ticket' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('configuracion');
    }

    public function down()
    {
        $this->forge->dropTable('configuracion');
    }
}
