<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'configuracion';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['identidad', 'nombre', 'telefono', 'direccion', 'correo', 'mensaje', 'mensaje_ticket', 'tasa_interes', 'cuotas', 'logo'];
    protected $useTimestamps = false;
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}
//modelo de admin