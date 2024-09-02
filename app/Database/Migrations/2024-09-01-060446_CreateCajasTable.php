<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCajasTable extends Migration
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
            'monto_inicial' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'fecha_apertura' => [
                'type'       => 'DATETIME',
                'null'       => false,
            ],
            'ganancia' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
            ],
            'estado' => [
                'type'       => 'INT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'id_usuario' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_usuario');
        $this->forge->createTable('cajas');
    }

    public function down()
    {
        $this->forge->dropTable('cajas');
    }
}
