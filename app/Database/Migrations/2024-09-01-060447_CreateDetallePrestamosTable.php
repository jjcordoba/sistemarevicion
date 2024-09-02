<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetallePrestamosTable extends Migration
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
            'cuota' => [
                'type'       => 'INT',
            ],
            'abono' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
            ],
            'f_vence' => [
                'type'       => 'DATE',
                'null'       => true,
            ],
            'importe_cuota' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'estado' => [
                'type'       => 'INT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'observaciones' => [
                'type'       => 'LONGTEXT',
                'null'       => true,
            ],
            'id_prestamo' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'fecha1' => [
                'type'    => 'TIMESTAMP',
                'default' => null,
            ],
            'c_pagadas' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'id_pago' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'm_pago' => [
                'type'       => 'INT',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_prestamo');
        $this->forge->createTable('detalle_prestamos');
    }

    public function down()
    {
        $this->forge->dropTable('detalle_prestamos');
    }
}
