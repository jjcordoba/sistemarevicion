<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\ClientesModel;
use App\Models\DetallePrestamosModel;
use App\Models\PrestamosModel;
use App\Models\UsuariosModel;
use Config\Database;
use ZipArchive;

class Admin extends BaseController
{
    protected $reglas, $admin, $clientes, $prestamos, $detalle, $usuarios;
    protected $session;

    public function __construct()
    {
        helper(['form', 'function']);
        $this->admin = new AdminModel();
        $this->usuarios = new UsuariosModel();
        //$this->clientes = new ClientesModel();
        //$this->prestamos = new PrestamosModel();
        //$this->detalle = new DetallePrestamosModel();
        $this->session = session();

        log_message('debug', 'Session data: ' . json_encode($this->session->get()));

        // Verificar si el usuario está autenticado
        if (!$this->session->get('logged_in')) {
            return redirect()->to(base_url('auth/login'));
        }
        helper(['url', 'form']);
        session();
    }

    public function home()
    {
        if (!session()->get('status')) {
            echo 'NO';
          }else{
              echo session()->get('status');
          }
        //  return $this->loadView('admin/home');
    }

    public function modificar()
    {
        if (!in_array('modificar configuracion', $this->session->get('permisos'))) {
            return view('permisos');
        }

        $this->reglas = [
            'identidad' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El {field} es requerido'
                ]
            ],
            'nombre' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El {field} es requerido'
                ]
            ],
            'telefono' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El {field} es requerido'
                ]
            ],
            'direccion' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El {field} es requerido'
                ]
            ],
            'cuotas' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El {field} es requerido'
                ]
            ],
            'mensaje_ticket' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El {field} es requerido'
                ]
            ]
        ];

        if ($this->request->getMethod() == 'post' && $this->validate($this->reglas)) {
            $data = [
                'identidad' => $this->request->getPost('identidad'),
                'nombre' => $this->request->getPost('nombre'),
                'telefono' => $this->request->getPost('telefono'),
                'direccion' => $this->request->getPost('direccion'),
                'correo' => $this->request->getPost('correo'),
                'mensaje' => $this->request->getPost('mensaje'),
                'mensaje_ticket' => $this->request->getPost('mensaje_ticket'),
                'tasa_interes' => (!empty($this->request->getPost('tasa_interes'))) ? $this->request->getPost('tasa_interes') : 0,
                'cuotas' => $this->request->getPost('cuotas')
            ];

            $this->admin->update($this->request->getPost('id'), $data);
            return redirect()->to(base_url('admin'))->with('modificado', 'Datos modificados con éxito.');
        } else {
            $data['validation'] = $this->validator;
            $data['data'] = $this->admin->first();
            return $this->loadView('admin/index', $data);
        }
    }

    public function dashboard()
    {
       
                /* if (service('auth')->checkAccess('ver_dashboard') !== true) {
                    return redirect()->to(base_url('sinpermisos'));
                } */

               // if (!session()->get('status')) {
               //     return redirect()->to('/sisesion3');
                //}
             

        $db = Database::connect();

        // Consultas para obtener datos
        $query_clientes = "SELECT COUNT(*) as total_clientes FROM clientes";
        $result_clientes = $db->query($query_clientes);
        $total_clientes = $result_clientes->getRow()->total_clientes;

        $query_prestamos = "SELECT COUNT(*) as total_prestamos FROM prestamos";
        $result_prestamos = $db->query($query_prestamos);
        $total_prestamo = $result_prestamos->getRow()->total_prestamos;

        $query_usuarios = "SELECT COUNT(*) as total_usuarios FROM usuarios";
        $result_usuarios = $db->query($query_usuarios);
        $total_usuarios = $result_usuarios->getRow()->total_usuarios;

        $query_monto_inicial = "SELECT SUM(monto_inicial) AS total_monto_inicial FROM cajas";
        $result_monto_inicial = $db->query($query_monto_inicial);
        $total_monto_inicial = $result_monto_inicial->getRow()->total_monto_inicial;

        $query_cobros = "SELECT SUM(monto) AS total_cobros FROM detalle_pagos";
        $result_cobros = $db->query($query_cobros);
        $total_cobros = $result_cobros->getRow()->total_cobros;

        $query_prestamos_total = "SELECT SUM(importe) AS total_prestamos FROM prestamos";
        $result_prestamos_total = $db->query($query_prestamos_total);
        $total_prestamos = $result_prestamos_total->getRow()->total_prestamos;

        $total_caja = $total_monto_inicial + $total_cobros - $total_prestamos;

        $data = [
            'total_clientes' => $total_clientes,
            'total_prestamo' => $total_prestamo,
            'total_usuarios' => $total_usuarios,
            'total_caja' => $total_caja,
        ];

        return $this->loadView('admin/dashboard', $data);
    }

    public function vencidos()
    {
        if (!in_array('ver prestamos vencidos', $this->session->get('permisos'))) {
            return view('permisos');
        }

        $fecha = date('Y-m-d');
        $this->prestamos->select('prestamos.f_venc, clientes.nombre, clientes.apellido');
        $this->prestamos->join('clientes', 'prestamos.id_cliente = clientes.id');
        $data = $this->prestamos->where("prestamos.f_venc < '$fecha' AND prestamos.estado = 1")->findAll();

        foreach ($data as &$row) {
            $row['color'] = '#' . substr(md5($row['nombre']), 0, 6);
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function createBackup()
    {
        if (!in_array('crear respaldo', $this->session->get('permisos'))) {
            return view('permisos');
        }

        $db = Database::connect();
        $tables = $db->listTables();
        $backupData = '';

        foreach ($tables as $table) {
            $query = $db->query("SELECT * FROM $table");
            $result = $query->getResultArray();

            if (!empty($result)) {
                $backupData .= "-- --------------------------------------------------------\n";
                $backupData .= "-- Estructura de tabla para $table\n";
                $backupData .= "-- --------------------------------------------------------\n\n";

                $schemaQuery = $db->query("SHOW CREATE TABLE $table");
                $schema = $schemaQuery->getRow()->{'Create Table'};
                $backupData .= $schema . ";\n\n";

                $backupData .= "-- --------------------------------------------------------\n";
                $backupData .= "-- Datos de tabla para $table\n";
                $backupData .= "-- --------------------------------------------------------\n\n";

                foreach ($result as $row) {
                    $values = array_map(function ($value) {
                        return "'" . str_replace("'", "''", $value) . "'";
                    }, $row);

                    $backupData .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
                }

                $backupData .= "\n";
            }
        }

        $backupFilename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $backupPath = WRITEPATH . 'backups/' . $backupFilename;

        if (!file_exists(WRITEPATH . 'backups')) {
            mkdir(WRITEPATH . 'backups');
        }

        if (file_put_contents($backupPath, $backupData) !== false) 
        { if ($this->crearZip($backupPath)) { unlink($backupPath);
             return $this->response->download('./backup.zip', 'archivo.zip'); 
            }
              else {
                 return redirect()->to(base_url('admin/dashboard'))->with('message', 'Error al crear zip'); 
            } 
        } 
        else { 
            return redirect()->to(base_url('admin/dashboard'))->with('message', 'Error al crear el respaldo.'); } }

            public function crearZip($ruta)
            {
                $zip = new ZipArchive();
                $zipFilename = 'backup.zip';
            
                if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                    $zip->addFile($ruta, basename($ruta));
                    $zip->close();
                    return true;
                } else {
                    return false;
                }
            }
            
            public function comparacion($anio)
            {
                if (!in_array('ver comparacion', $this->session->get('permisos'))) {
                    return view('permisos');
                }
            
                $desde = $anio . '-01-01 00:00:00';
                $hasta = $anio . '-12-31 23:59:59';
                $id_usuario = $this->session->id_usuario;
                $this->prestamos->select("SUM(IF(MONTH(fecha) = 1, importe, 0)) AS ene,
                    SUM(IF(MONTH(fecha) = 2, importe, 0)) AS feb,
                    SUM(IF(MONTH(fecha) = 3, importe, 0)) AS mar,
                    SUM(IF(MONTH(fecha) = 4, importe, 0)) AS abr,
                    SUM(IF(MONTH(fecha) = 5, importe, 0)) AS may,
                    SUM(IF(MONTH(fecha) = 6, importe, 0)) AS jun,
                    SUM(IF(MONTH(fecha) = 7, importe, 0)) AS jul,
                    SUM(IF(MONTH(fecha) = 8, importe, 0)) AS ago,
                    SUM(IF(MONTH(fecha) = 9, importe, 0)) AS sep,
                    SUM(IF(MONTH(fecha) = 10, importe, 0)) AS oct,
                    SUM(IF(MONTH(fecha) = 11, importe, 0)) AS nov,
                    SUM(IF(MONTH(fecha) = 12, importe, 0)) AS dic");
                $this->prestamos->where('fecha >=', $desde);
                $this->prestamos->where('fecha <=', $hasta);
                $this->prestamos->where('id_usuario', $id_usuario);
                $result = $this->prestamos->get()->getRowArray();
            
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
                die();
            }
            
            protected function loadView($view, $data = [])
            {
                echo view("templates/header");
                echo view($view, $data);
                echo view("templates/footer");
            }
        }            