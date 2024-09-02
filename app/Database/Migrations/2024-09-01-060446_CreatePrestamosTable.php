<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrestamosTable extends Migration
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
            'importe' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'modalidad' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'tasa_interes' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'cuotas' => [
                'type'       => 'INT',
            ],
            'fecha' => [
                'type'       => 'DATETIME',
            ],
            'f_venc' => [
                'type'       => 'DATE',
                'null'       => true,
            ],
            'estado' => [
                'type'       => 'INT',
                'constraint' => 1,
                'default'    => 1,
            ],
            't_estado' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'id_cliente' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'id_usuario' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_cliente');
        $this->forge->addKey('id_usuario');
        $this->forge->createTable('prestamos');
    }

    public function down()
    {
        $this->forge->dropTable('prestamos');
    }
}
