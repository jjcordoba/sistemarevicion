<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public $defaultGroup = 'default';
    public $default = [];

    public function __construct()
    {
        parent::__construct();
        // Asignar valores dinámicos dentro del constructor
        $this->default = [
            'DSN'      => '',
            'hostname' => getenv('database.default.hostname'),
            'username' => getenv('database.default.username'),
            'password' => getenv('database.default.password'),
            'database' => getenv('database.default.database'),
            'DBDriver' => getenv('database.default.DBDriver'),
            'DBPrefix' => getenv('DB_PREFIX'),
            'pConnect' => false,
            'DBDebug'  => (ENVIRONMENT !== 'production'),
            'cacheOn'  => false,
            'cacheDir' => '',
            'charset'  => 'utf8mb4',
            'DBCollat' => 'utf8mb4_general_ci',
            'swapPre'  => '',
            'encrypt'  => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port'     => getenv('database.default.port'),
        ];
    }
}
