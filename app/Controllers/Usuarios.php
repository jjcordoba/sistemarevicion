<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\RolesModel;
use App\Models\EmpresaModel;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class Usuarios extends BaseController
{
    use ResponseTrait;

    protected $usuarios;
    protected $roles;
    protected $empresaModel;
    protected $session;
    protected $reglas;

    public function __construct()
    {
        helper(['form', 'function']);
        $this->usuarios = new UsuariosModel();
        $this->roles = new RolesModel();
        $this->empresaModel = new EmpresaModel();
        $this->session = session();

        $this->reglas = [
            'id' => 'is_natural',
            'nombre' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es requerido'
                ]
            ],
            'apellido' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es requerido'
                ]
            ],
            'correo' => [
                'rules' => 'required|valid_email|is_unique[usuarios.correo,id,{id}]',
                'errors' => [
                    'required' => 'El campo {field} es requerido',
                    'valid_email' => 'Ingresa un correo válido',
                    'is_unique' => 'El {field} debe ser único'
                ]
            ],
            'telefono' => [
                'rules' => 'required|min_length[8]|is_unique[usuarios.telefono,id,{id}]',
                'errors' => [
                    'required' => 'El campo {field} es requerido',
                    'is_unique' => 'El {field} debe ser único',
                    'min_length' => 'El {field} debe contener mínimo 8 caracteres'
                ]
            ],
            'direccion' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es requerido'
                ]
            ],
            'clave' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'El campo {field} es requerido',
                    'min_length' => 'La {field} debe contener mínimo 6 caracteres'
                ]
            ],
            'confirmar' => [
                'rules' => 'required|min_length[6]|matches[clave]',
                'errors' => [
                    'required' => 'El campo {field} es requerido',
                    'min_length' => 'La {field} debe contener mínimo 6 caracteres',
                    'matches' => 'Las contraseñas no coinciden'
                ]
            ],
            'rol' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo {field} es requerido'
                ]
            ]
        ];
    }

    public function getEmail()
    {
        $email = $this->session->get('correo'); // Obtener el email del usuario desde la sesión

        if ($email) {
            $usuario = $this->usuarios->getUsuarioPorCorreo($email);
            if ($usuario) {
                return $this->respond(['email' => $usuario['correo']]);
            } else {
                return $this->failNotFound('Usuario no encontrado');
            }
        } else {
            return $this->failNotFound('Correo no encontrado en la sesión');
        }
    }

    public function index()
    {
        if (!$this->checkPermission('listar usuarios')) {
            return $this->failForbidden('No tienes permiso para listar usuarios');
        }

        $data['script'] = 'usuarios.js';        
        $data['es_superadmin'] = $this->session->get('es_superadmin');
        if ($data['es_superadmin']) {
            $data['empresas'] = $this->empresaModel->findAll();
        }
        return view("templates/header") . view("usuarios/index", $data) . view("templates/footer");
    }
    
    public function listar()
    {
        if (!$this->checkPermission('listar usuarios')) {
            return $this->failForbidden('No tienes permiso para listar usuarios');
        }
    
        $es_superadmin = $this->session->get('es_superadmin');
        $id_empresa = $this->session->get('id_empresa');

        if ($es_superadmin) {
            $usuarios = $this->usuarios->findAll();
        } else {
            $usuarios = $this->usuarios->where('id_empresa', $id_empresa)->findAll();
        }
    
        $data = [];
        foreach ($usuarios as $usuario) {
            if ($this->session->email === 'superadmin@jtdesarrolloweb.com' || $usuario['correo'] !== 'superadmin@jtdesarrolloweb.com') {
                if ($usuario['estado'] == 1) {
                    $usuario['estado'] = '<span class="badge bg-success px-1">Activo</span>';
                    $usuario['acciones'] = '<div class="text-center">
                        <a href="' . base_url("usuarios/editar/" . $usuario['id']) . '" class="btn btn-outline-info"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-outline-danger" onclick="eliminarUsuario(' . $usuario['id'] . ')"><i class="fas fa-trash"></i> Eliminar</button>
                    </div>';
                } else {
                    $usuario['estado'] = '<span class="badge bg-secondary px-1">Inactivo</span>';
                    $usuario['acciones'] = '<div class="text-center">
                        <a href="' . base_url("usuarios/editar/" . $usuario['id']) . '" class="btn btn-outline-info"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-outline-danger" onclick="eliminarUsuario(' . $usuario['id'] . ')"><i class="fas fa-trash"></i> Eliminación permanente</button>
                    </div>';
                }
                $data[] = $usuario;
            }
        }
    
        return $this->respond($data);
    }

    public function registrar()
    {
        if (!$this->checkPermission('crear usuario')) {
            return $this->failForbidden('No tienes permiso para crear usuarios');
        }

        $es_superadmin = $this->session->get('es_superadmin');
        $id_empresa = $this->session->get('id_empresa');

        if (!$es_superadmin) {
            $empresa = $this->empresaModel->find($id_empresa);
            $usuarios_actuales = $this->usuarios->where('id_empresa', $id_empresa)->countAllResults();

            if ($usuarios_actuales >= $empresa['limite_usuarios']) {
                return $this->fail('Has alcanzado el límite de usuarios para tu empresa');
            }
        }

        if ($this->request->getMethod() == 'post' && $this->validate($this->reglas)) {
            $clave = password_hash($this->request->getVar('clave'), PASSWORD_DEFAULT);
            $data = [
                'nombre' => $this->request->getVar('nombre'),
                'apellido' => $this->request->getVar('apellido'),
                'correo' => $this->request->getVar('correo'),
                'telefono' => $this->request->getVar('telefono'),
                'direccion' => $this->request->getVar('direccion'),
                'clave' => $clave,
                'id_rol' => $this->request->getVar('rol'),
                'id_empresa' => $es_superadmin ? $this->request->getVar('id_empresa') : $id_empresa
            ];
            $this->usuarios->save($data);
            return redirect()->to(base_url() . 'usuarios')->with('message', 'Usuario registrado con éxito.');
        } else {
            $data['validation'] = $this->validator;
            $data['roles'] = $this->roles->where('estado', 1)->findAll();
            $data['rol'] = $this->request->getVar('rol');
            if ($es_superadmin) {
                $data['empresas'] = $this->empresaModel->findAll();
            }
            return view("templates/header") . view("usuarios/nuevo", $data) . view("templates/footer");
        }
    }

    public function nuevo()
    {
        if (!$this->checkPermission('crear usuario')) {
            return $this->failForbidden('No tienes permiso para crear usuarios');
        }

        $data['roles'] = $this->roles->where('estado', 1)->findAll();
        if ($this->session->get('es_superadmin')) {
            $data['empresas'] = $this->empresaModel->findAll();
        }
        return view("templates/header") . view("usuarios/nuevo", $data) . view("templates/footer");
    }

    public function editar($id)
    {
        if (!$this->checkPermission('editar usuario')) {
            return $this->failForbidden('No tienes permiso para editar usuarios');
        }

        $data['usuario'] = $this->usuarios->where('id', $id)->first();
        $data['roles'] = $this->roles->where('estado', 1)->findAll();
        if ($this->session->get('es_superadmin')) {
            $data['empresas'] = $this->empresaModel->findAll();
        }
        return view("templates/header") . view("usuarios/editar", $data) . view("templates/footer");
    }

    public function actualizar()
    {
        if (!$this->checkPermission('editar usuario')) {
            return $this->failForbidden('No tienes permiso para editar usuarios');
        }

        $this->reglas['correo']['rules'] = 'required|valid_email|is_unique[usuarios.correo,id,{id}]';
        $this->reglas['telefono']['rules'] = 'required|min_length[8]|is_unique[usuarios.telefono,id,{id}]';
        
        if ($this->request->getMethod() == 'post' && $this->validate($this->reglas)) {
            $data = $this->usuarios->update(
                $this->request->getVar('id'),
                [
                    'nombre' => $this->request->getVar('nombre'),
                    'apellido' => $this->request->getVar('apellido'),
                    'correo' => $this->request->getVar('correo'),
                    'telefono' => $this->request->getVar('telefono'),
                    'direccion' => $this->request->getVar('direccion'),
                    'id_rol' => $this->request->getVar('rol')
                ]
            );
            return redirect()->to(base_url() . 'usuarios')->with('message', 'Usuario modificado con éxito.');
        } else {
            $data['validation'] = $this->validator;
            $data['roles'] = $this->roles->where('estado', 1)->findAll();
            $data['usuario'] = $this->usuarios->where('id', $this->request->getVar('id'))->first();
            return view("templates/header") . view("usuarios/editar", $data) . view("templates/footer");
        }
    }

    public function eliminar($id)
    {
        if (!$this->checkPermission('eliminar usuario')) {
            return $this->failForbidden('No tienes permiso para eliminar usuarios');
        }

        $data = $this->usuarios->update($id, ['estado' => 0]);
        if ($data) {
            return $this->respond(['icono' => 'success', 'mensaje' => 'Usuario eliminado con éxito']);
        } else {
            return $this->fail('Error al eliminar el usuario');
        }
    }

    public function salir()
    {
        $this->session->destroy();
        return redirect()->to(base_url());
    }

    public function perfil()
    {
        return view("templates/header") . view("usuarios/perfil") . view("templates/footer");
    }

    public function cambiar()
    {
        $this->reglas = [
            'clave_actual' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'La clave actual es requerida',
                    'min_length' => 'La clave debe contener mínimo 6 caracteres'
                ]
            ],
            'clave_nueva' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'La clave nueva es requerida',
                    'min_length' => 'La clave nueva debe contener mínimo 6 caracteres'
                ]
            ],
            'confirmar' => [
                'rules' => 'required|min_length[6]|matches[clave_nueva]',
                'errors' => [
                    'required' => 'El campo {field} es requerido',
                    'min_length' => 'El {field} debe contener mínimo 6 caracteres',
                    'matches' => 'Las contraseñas no coinciden'
                ]
            ]
        ];

        if ($this->request->getMethod() == "post" && $this->validate($this->reglas)) {
            $session = session();
            $id = $session->get('id_usuario');
            $clave = $this->request->getVar('clave_actual');
            $user = $this->usuarios->where('id', $id)->first();

            if (password_verify($clave, $user['clave'])) {
                $hash = password_hash($this->request->getVar('clave_nueva'), PASSWORD_DEFAULT);
                $data = $this->usuarios->update($id, ['clave' => $hash]);

                if ($data) {
                    return redirect()->to(base_url() . 'usuarios/perfil')->with('perfil', 'ok');
                } else {
                    return redirect()->to(base_url() . 'usuarios/perfil')->with('perfil', 'error');
                }
            } else {
                return redirect()->to(base_url() . 'usuarios/perfil')->with('perfil', 'incorrecta');
            }
        } else {
            $data['validation'] = $this->validator;
            return view("templates/header") . view("usuarios/perfil", $data) . view("templates/footer");
        }
    }

    private function checkPermission($action)
    {
        return verificar($action, $this->session->get('permisos'));
    }
}
