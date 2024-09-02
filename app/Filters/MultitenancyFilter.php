<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Multitenancy;
use App\Models\EmpresaModel;

class MultitenancyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $multitenancy = new Multitenancy();
        $subdomain = $multitenancy->getSubdomain();

        if ($subdomain) {
            $empresaModel = new EmpresaModel();
            $empresa = $empresaModel->where('subdominio', $subdomain)->first();

            if ($empresa) {
                $db = db_connect();
                $db->setDatabase($multitenancy->getDatabaseName($subdomain));
            } else {
                // Si el subdominio no existe, redirigir al dominio principal
                return redirect()->to(site_url());
            }
        } else {
            // Si no hay subdominio, asegúrate de usar la base de datos principal
            $db = db_connect();
            $db->setDatabase(env('database.default.database'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No necesitamos hacer nada después de la solicitud
    }
}
