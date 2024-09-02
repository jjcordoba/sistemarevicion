<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'correo', 'clave', 'id_rol', 'estado', 'es_superadmin', 'id_empresa'];

    public function getUsuarioPorCorreo($correo)
    {
        return $this->where('correo', $correo)->first();
    }
}
