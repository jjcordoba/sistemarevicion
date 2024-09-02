<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMetodosPagoTable extends Migration
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
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'estado' => [
                'type'       => 'INT',
                'constraint' => 1,
                'default'    => 1,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('metodos_pago');
    }

    public function down()
    {
        $this->forge->dropTable('metodos_pago');
    }
}
