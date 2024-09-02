<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        //$session->set(['hola'=>'si']);
        
        /* echo json_encode($session->get());
        return; */

        if (!$session->get('logged_in')) {
            // Si no está autenticado, redirigir al login
            return redirect()->to(base_url('login'));
        }

        // Verificar si el usuario pertenece a la empresa actual
        /* $currentCompany = (new \App\Controllers\BaseController())->getCurrentCompany();

        if ($currentCompany && $session->get('id_empresa') != $currentCompany['id']) {
            // Si no pertenece a la empresa actual, destruir la sesión y redirigir al login
            $session->destroy();
            return redirect()->to(base_url('login'))->with('msg', 'No tienes acceso a esta empresa.');
        } */
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No necesitamos hacer nada después de la solicitud
    }
}
