<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Multitenancy extends BaseConfig
{
    public $mainDomain = '';
    public $dbPrefix = '';
    public $defaultCompanyId = 1; // ID de la empresa por defecto

    public function __construct()
    {
        parent::__construct();
        $this->mainDomain = env('app.baseURL', 'http://localhost');
        $this->dbPrefix = env('DB_PREFIX', 'empresa_');
    }

    public function getSubdomain()
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            return null;
        }

        $host = $_SERVER['HTTP_HOST'];
        $parts = explode('.', $host);
        if (count($parts) > 2) {
            return $parts[0];
        }
        return null;
    }

    public function getDatabaseName($subdomain)
    {
        return $this->dbPrefix . $subdomain;
    }
}
