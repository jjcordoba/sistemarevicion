<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\RolesModel;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController
{
    use ResponseTrait;

    protected $usuarios;
    protected $roles;
    protected $session; // Propiedad para la sesión
    protected $permisos; // Propiedad para los permisos

    public function __construct()
    {
        $this->usuarios = new UsuariosModel();
        $this->roles = new RolesModel();
        $this->session = session(); // Inicializa la sesión
        $this->permisos = []; // Inicializa la propiedad de permisos
        log_message('info', 'Auth Controller initialized.');
    }

    public function login()
    {
        log_message('info', 'login() method called.');

        if ($this->session->get('logged_in')) {
            log_message('info', 'User is already logged in, redirecting to admin/home.');
            return redirect()->to(base_url('admin/home'));
        }
        
        log_message('info', 'Displaying login view.');
        return view('login');
    }

    public function auth()
    {
        log_message('info', 'auth() method called.');
        log_message('info', 'Request method: ' . $this->request->getMethod());
        log_message('info', 'Request data: ' . json_encode($this->request->getVar()));

        $rules = [
            'correo' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'El campo {field} es requerido',
                    'valid_email' => 'Debe ingresar un correo electrónico válido'
                ]
            ],
            'clave' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es requerido'
                ]
            ]
        ];

        if ($this->request->getMethod() === 'post') {
            log_message('info', 'POST method detected.');

            if ($this->validate($rules)) {
                log_message('info', 'Validation passed.');

                $correo = $this->request->getVar('correo');
                $clave = $this->request->getVar('clave');
                log_message('info', 'User credentials received: ' . $correo);

                $datosUser = $this->usuarios->where(['correo' => $correo, 'estado' => 1])->first();

                if ($datosUser) {
                    log_message('info', 'User found in database.');

                    if (password_verify($clave, $datosUser['clave'])) {
                        log_message('info', 'Password verified successfully.');

                        $roles = $this->roles->find($datosUser['id_rol']);

                        if ($roles) {
                            log_message('info', 'Role found for user.');

                            $this->permisos = $roles['permisos'] ? json_decode($roles['permisos'], true) : [];
                            $es_superadmin = isset($datosUser['es_superadmin']) && $datosUser['es_superadmin'];

                            // Configuración de la sesión
                            $this->setUserSession($datosUser, $roles['nombre'], $this->permisos, $es_superadmin);

                            // Generación del token
                            $token = bin2hex(random_bytes(16));
                            log_message('info', 'Generated auth token: ' . $token);

                            // Añadir token al encabezado de respuesta
                            $this->response->setHeader('Authorization', 'Bearer ' . $token);
                            log_message('info', 'Auth token added to response headers.');

                            $redirect_url = base_url('admin/home');
                            $response_data = [
                                'logged_in' => true,
                                'status' => 'success',
                                'message' => 'Autenticación exitosa.',
                                'data' => [
                                    'id_usuario' => $datosUser['id'],
                                    'correo' => $datosUser['correo'],
                                    'nombre' => $datosUser['nombre'],
                                    'rol' => $roles['nombre'],
                                    'permisos' => $this->permisos,
                                    'es_superadmin' => $es_superadmin,
                                    'id_empresa' => $datosUser['id_empresa'],
                                    'token' => $token,
                                ],
                                'redirect_url' => $redirect_url
                            ];
                            log_message('info', 'Login successful. Response data: ' . json_encode($response_data));

                            if ($this->request->isAJAX() || $this->isApiRequest()) {
                                return $this->respond($response_data);
                            }

                            // Redirige al dashboard si no es una solicitud AJAX
                            return redirect()->to($redirect_url);
                        } else {
                            log_message('error', 'Role not found for user.');
                            return redirect()->to(base_url('cedeincorrecta'));
                        }
                    } else {
                        log_message('error', 'Password verification failed.');
                        return redirect()->to(base_url('cedeincorrecta'));
                    }
                } else {
                    log_message('error', 'User not found in database.');
                    return redirect()->to(base_url('cedeincorrecta'));
                }
            } else {
                log_message('error', 'Validation failed. Errors: ' . json_encode($this->validator->getErrors()));
                return redirect()->to(base_url('cedeincorrecta'));
            }
        } else {
            log_message('error', 'Request method is not POST.');
            return redirect()->to(base_url('cedeincorrecta'));
        }
    }

    private function setUserSession($datosUser, $rol, $permisos, $es_superadmin)
    {
        // Establece los datos de la sesión
        $this->session->set([
            'id_usuario' => $datosUser['id'],
            'correo' => $datosUser['correo'],
            'nombre' => $datosUser['nombre'],
            'rol' => $rol,
            'permisos' => $permisos,
            'es_superadmin' => $es_superadmin,
            'id_empresa' => $datosUser['id_empresa'],
            'logged_in' => true,
        ]);

        log_message('info', 'User session set with role and permissions.');
    }

    public function checkAccess($requiredPermission = null)
    {
        // Verifica si el usuario está autenticado
        if (!$this->session->get('logged_in')) {
            return redirect()->to(base_url('cedeincorrecta'));
        }

        // Verifica si el usuario tiene el rol y los permisos adecuados
        if ($requiredPermission !== null && !in_array($requiredPermission, $this->session->get('permisos'))) {
            return redirect()->to(base_url('sinpermisos'));
        }

        // Si todo está correcto
        return true;
    }

    private function handleErrorResponse($message, $code)
    {
        if ($this->request->isAJAX() || $this->isApiRequest()) {
            return $this->fail($message, $code);
        }
        return redirect()->back()->withInput()->with('error', $message);
    }

    private function isApiRequest()
    {
        return $this->request->getHeaderLine('Accept') === 'application/json' || $this->request->getHeaderLine('Content-Type') === 'application/json';
    }
}
