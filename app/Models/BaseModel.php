<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $empresaId;

    public function __construct()
    {
        parent::__construct();
        $this->empresaId = session()->get('empresa_id');
    }

    public function find($id = null, $columns = '*')
    {
        $this->where('id_empresa', $this->empresaId);
        return parent::find($id, $columns);
    }

    public function findAll(int $limit = 0, int $offset = 0)
    {
        $this->where('id_empresa', $this->empresaId);
        return parent::findAll($limit, $offset);
    }

    // Sobrescribe otros métodos según sea necesario
}
