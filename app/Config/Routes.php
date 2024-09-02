<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('auth', 'Auth::auth');
$routes->get('logout', 'Auth::logout');

$routes->get('permisos', 'Home::permisos');
$routes->post('usuarios/validar', 'Usuarios::verificar');

$routes->group('', ['filter' => 'AuthCheck'], function ($routes) {
	$routes->get('usuarios', 'Usuarios::index');
	$routes->get('usuarios/nuevo', 'Usuarios::nuevo');
	$routes->get('usuarios/listar', 'Usuarios::listar');
	$routes->get('usuarios/salir', 'Usuarios::salir');
	$routes->post('usuarios/registrar', 'Usuarios::registrar');
	$routes->post('usuarios/actualizar', 'Usuarios::actualizar');
	$routes->get('usuarios/editar/(:num)', 'Usuarios::editar/$1');
	$routes->get('usuarios/eliminar/(:num)', 'Usuarios::eliminar/$1');
	$routes->get('usuarios/perfil', 'Usuarios::perfil');
	$routes->post('usuarios/cambiar', 'Usuarios::cambiar');

	$routes->get('clientes', 'Clientes::index');
	$routes->get('clientes/nuevo', 'Clientes::nuevo');
	$routes->get('clientes/listar', 'Clientes::listar');
	$routes->post('clientes/registrar', 'Clientes::registrar');
	$routes->post('clientes/documentos', 'Clientes::documentos');
	$routes->post('clientes/actualizar', 'Clientes::actualizar');
	$routes->post('clientes/eliminarImg', 'Clientes::eliminarImg');
	$routes->get('clientes/editar/(:num)', 'Clientes::editar/$1');
	$routes->get('clientes/eliminar/(:num)', 'Clientes::eliminar/$1');
	$routes->get('clientes/docs/(:num)', 'Clientes::docs/$1');

	$routes->get('roles', 'Roles::index');
	$routes->get('roles/nuevo', 'Roles::nuevo');
	$routes->get('roles/listar', 'Roles::listar');
	$routes->post('roles/registrar', 'Roles::registrar');
	$routes->post('roles/actualizar', 'Roles::actualizar');
	$routes->get('roles/editar/(:num)', 'Roles::editar/$1');
	$routes->get('roles/eliminar/(:num)', 'Roles::eliminar/$1');

	$routes->get('cajas', 'Cajas::index');
	$routes->get('cajas/nuevo', 'Cajas::nuevo');
	$routes->get('cajas/listar', 'Cajas::listar');
	$routes->get('cajas/movimientos', 'Cajas::movimientos');
	$routes->post('cajas/registrar', 'Cajas::registrar');
	$routes->post('cajas/actualizar', 'Cajas::actualizar');
	$routes->get('cajas/editar/(:num)', 'Cajas::editar/$1');
	$routes->get('cajas/eliminar/(:num)', 'Cajas::eliminar/$1');

	$routes->get('prestamos', 'Prestamos::index');
	$routes->get('prestamos/historial/(:any)', 'Prestamos::historial/$1');
	$routes->get('prestamos/buscarCliente', 'Prestamos::buscarCliente');
	$routes->get('prestamos/listar/(:any)', 'Prestamos::listar/$1');
	$routes->get('prestamos/restante/(:num)', 'Prestamos::restante/$1');
	$routes->get('prestamos/getAbonos/(:num)', 'Prestamos::getAbonos/$1');
	$routes->get('prestamos/contrato/(:any)', 'Prestamos::contrato/$1');
	$routes->get('prestamos/cambiarEstado/(:num)', 'Prestamos::cambiarEstado/$1');
	$routes->get('prestamos/(:num)/ticketPago', 'Prestamos::ticketPago/$1');
	$routes->get('prestamos/eliminar/(:num)', 'Prestamos::eliminar/$1');
	$routes->post('prestamos/enviarCorreo', 'Prestamos::enviarCorreo');
	$routes->post('prestamos/registrar', 'Prestamos::registrar');
	$routes->post('prestamos/agregarAbono', 'Prestamos::agregarAbono');
	$routes->post('prestamos/cancelarAbono', 'Prestamos::cancelarAbono');

	$routes->get('admin', 'Admin::index');
	$routes->get('admin/dashboard', 'Admin::dashboard');
	$routes->get('admin/home', 'Admin::home');
	$routes->get('admin/grafico/(:any)', 'Admin::comparacion/$1');
	$routes->get('admin/vencidos', 'Admin::vencidos');
	$routes->get('backup', 'Admin::createBackup');
	$routes->get('pdf/(:any)', 'Reportes::pdf/$1');
	$routes->get('excel/(:any)', 'Reportes::excel/$1');
	$routes->post('admin/modificar', 'Admin::modificar');
	$routes->get('reportes/(:any)/abonos', 'Reportes::abonos/$1');
	$routes->get('reportes/interes/(:any)/(:any)', 'Reportes::interes/$1/$2');
	$routes->get('reportes/cobros/(:any)/(:any)', 'Reportes::cobros/$1/$2');
	$routes->get('reportes/consultas/(:any)/(:any)', 'Reportes::consultas/$1/$2');
	$routes->get('reportes/(:any)/(:any)/clienteAbono', 'Prestamos::clienteAbono/$1/$2');

	$routes->get('metodos', 'Metodos::index');
	$routes->get('metodos/nuevo', 'Metodos::nuevo');
	$routes->get('metodos/listar', 'Metodos::listar');
	$routes->post('metodos/registrar', 'Metodos::registrar');
	$routes->post('metodos/actualizar', 'Metodos::actualizar');
	$routes->get('metodos/editar/(:num)', 'Metodos::editar/$1');
	$routes->get('metodos/eliminar/(:num)', 'Metodos::eliminar/$1');

	$routes->get('movimientos', 'Movimientos::index');
	$routes->get('movimientos/nuevo', 'Movimientos::nuevo');
	$routes->get('movimientos/listar', 'Movimientos::listar');
	$routes->post('movimientos/registrar', 'Movimientos::registrar');
	$routes->post('movimientos/actualizar', 'Movimientos::actualizar');
	$routes->get('movimientos/editar/(:num)', 'Movimientos::editar/$1');
	$routes->get('movimientos/eliminar/(:num)', 'Movimientos::eliminar/$1');
	$routes->get('prestamos/hoy', 'Prestamos::hoy');
	$routes->get('prestamos/activos', 'Prestamos::activos');
	$routes->get('prestamos/vencidos', 'Prestamos::vencidos');
	$routes->get('prestamos/anulados', 'Prestamos::anulados');
	$routes->get('usuarios/email', 'Usuarios::getEmail');
	$routes->get('pagos', 'DetallesPagos::index');
	$routes->get('pagos/listar', 'DetallesPagos::listar');
	$routes->post('pagos/registrar', 'DetallesPagos::registrar');
	$routes->post('pagos/editar', 'DetallesPagos::editar');
	$routes->post('pagos/actualizar', 'DetallesPagos::actualizar');
	$routes->delete('pagos/eliminar/(:num)', 'DetallesPagos::eliminar/$1');
	$routes->post('/pagos/editarPago', 'DetallesPagos::editarPago');
	$routes->get('pagos/listar', 'DetallesPagos::listar');

	// Nuevas rutas para empresas
	$routes->get('empresas', 'Empresas::index');
	$routes->get('empresas/nuevo', 'Empresas::nuevo');
	$routes->get('empresas/crear', 'Empresas::crear');
	$routes->post('empresas/registrar', 'Empresas::registrar');
	$routes->get('empresas/editar/(:num)', 'Empresas::editar/$1');
	$routes->post('empresas/actualizar', 'Empresas::actualizar');
	$routes->get('empresas/eliminar/(:num)', 'Empresas::eliminar/$1');

	$routes->get('crear-superadmin', 'Usuarios::crearSuperAdmin');

	// En app/Config/Routes.php
	$routes->add('some/route', 'SomeController::index', ['filter' => 'multitenancy']);
	$routes->post('usuarios/validar', 'Usuarios::validar');
});

$routes->group('{subdomain}', ['namespace' => 'App\Controllers'], function ($routes) {
	$routes->get('/', 'Home::index');
	// Incluye aquí todas las rutas que definiste anteriormente
	$routes->get('usuarios', 'Usuarios::index');
	$routes->get('clientes', 'Clientes::index');
	// ... (todas las demás rutas)
});

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
$routes->get('principal', 'Principal::index');
