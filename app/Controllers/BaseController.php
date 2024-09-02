<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Multitenancy;
use App\Models\EmpresaModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    protected $session;
    protected $currentCompany;
    protected $data = []; // Agregado para evitar la propiedad dinámica

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Constructor.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->session = \Config\Services::session();
        
        // Asegúrate de que 'es_superadmin' siempre esté definido
        $this->data['es_superadmin'] = $this->session->get('es_superadmin') ?? false;
        $this->data['permisos'] = $this->session->get('permisos') ?? [];

        // Asegúrate de que la sesión esté iniciada
        $session = session();

        // Obtén el ID de la empresa actual
        $empresaId = $session->get('empresa_id');

        // Pasa el ID de la empresa a todas las vistas
        $this->data['empresa_id'] = $empresaId;

        // Obtén los datos de la empresa actual
        //$empresaModel = new \App\Models\EmpresaModel();
        $this->data['empresa_actual'] = 1;//$empresaModel->find($empresaId);

        $this->currentCompany = $this->getCurrentCompany();
    }

    protected function loadView($view, $data = [])
    {
        $data = array_merge($this->data, $data);
        echo view('templates/header', $data);
        echo view($view, $data);
        echo view('templates/footer', $data);
    }

    protected function getCurrentCompany()
    {
        $multitenancy = new \Config\Multitenancy();
        $subdomain = $multitenancy->getSubdomain();
        
        if ($subdomain) {
            $empresaModel = new \App\Models\EmpresaModel();
            return $empresaModel->where('subdominio', $subdomain)->first();
        }
        
        return null;
    }
}
