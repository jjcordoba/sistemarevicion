<?php

namespace Config;

use App\Filters\AuthCheck;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'AuthCheck'     => AuthCheck::class,
        'multitenancy'  => \App\Filters\MultitenancyFilter::class,
    ];

    public array $globals = [
        'before' => [
            'csrf' => ['except' => ['auth']],  // Excluir 'auth' de la protección CSRF
            'multitenancy' => ['except' => ['auth/*', 'login', 'register']],
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        'auth' => [
            'except' => ['login', 'auth', 'logout']
        ]
    ];
}
