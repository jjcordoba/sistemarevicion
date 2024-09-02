<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use CodeIgniter\Session\Handlers\FileHandler;
use CodeIgniter\Session\Session;

class Services extends BaseService
{
    public static function session($config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('session', $config);
        }

        if (is_null($config)) {
            $config = new \Config\App();
        }

        // Configurar sesión antes de iniciar
        ini_set('session.save_path', $config->sessionSavePath);

        return new Session(new FileHandler($config, $config->sessionSavePath), $config);
    }
}
