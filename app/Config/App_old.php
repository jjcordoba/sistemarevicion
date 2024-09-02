<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use App\Libraries\DualSessionHandler;
use CodeIgniter\Session\Handlers\FileHandler;

class App extends BaseConfig
{
    // Base URL de la aplicación
    public $baseURL = '';

    public function __construct()
    {
        parent::__construct();
        $this->baseURL = env('app.baseURL', 'http://localhost');
    }

    public string $indexPage = 'index.php';
    public string $uriProtocol = 'REQUEST_URI';
    public string $defaultLocale = 'en';
    public bool $negotiateLocale = false;
    public array $supportedLocales = ['en'];
    public string $appTimezone = 'America/Lima';
    public string $charset = 'UTF-8';
    public bool $forceGlobalSecureRequests = false;

    // Configuración de sesiones, asegurando que se guarden en la base de datos
    public string $sessionDriver = FileHandler::class;// 'CodeIgniter\Session\Handlers\FileHandler';

    public string $sessionSavePath = WRITEPATH . 'session';
    public string $sessionDBGroup = 'default';
    public string $sessionCookieName = 'ci_session';
    public int $sessionExpiration = 7200; // 2 horas
    public bool $sessionMatchIP = false;
    public int $sessionTimeToUpdate = 99999200;
    public bool $sessionRegenerateDestroy = false;

    // Configuración de Cookies
    public string $cookiePrefix = '';
    public string $cookieDomain = '';
    public string $cookiePath = '/';
    public bool $cookieSecure = false;
    public bool $cookieHTTPOnly = true;
    public $cookieSameSite = 'Lax';

    public array $proxyIPs = [];

    // Protección CSRF (Desactivado para esta configuración)
    public bool $csrfProtection = false;
    public string $csrfTokenName = 'csrf_test_name';
    public string $csrfHeaderName = 'X-CSRF-TOKEN';
    public string $csrfCookieName = 'csrf_cookie_name';
    public int $csrfExpire = 7200; // Cambiado de $CSRFExpire a $csrfExpire
    public bool $csrfRegenerate = true;
    public bool $csrfRedirect = false;
    public string $csrfSameSite = 'Lax'; // Cambiado de $CSRFSameSite a $csrfSameSite

    public bool $cspEnabled = false;

    // Configuración adicional para manejo de tokens de autenticación
    public string $authTokenName = 'auth_token';
    public int $authTokenExpiration = 99999200; // 24 horas

    public bool $CSPEnabled = false; // Asegúrate de que esta propiedad esté definida
}
