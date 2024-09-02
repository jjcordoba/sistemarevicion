<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetallePagosTable extends Migration
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
            'monto' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'prestamo_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'fecha' => [
                'type' => 'DATE',
            ],
            'hora' => [
                'type' => 'TIME',
            ],
            'num' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'id_cliente' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'id_metodo' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'id_prestamo' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'n_pagadas' => [
                'type'       => 'INT',
            ],
            'n_pendiente' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'n_totales' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'importe_cuota' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'pmora1' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_cliente');
        $this->forge->addKey('id_metodo');
        $this->forge->addKey('id_prestamo');
        $this->forge->createTable('detalle_pagos');
    }

    public function down()
    {
        $this->forge->dropTable('detalle_pagos');
    }
}
