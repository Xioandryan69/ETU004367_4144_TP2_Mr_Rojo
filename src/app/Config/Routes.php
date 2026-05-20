<?php

use CodeIgniter\Router\RouteCollection;
use Config\Services;

/**
 * @var RouteCollection $routes
 */

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('AuthController');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('employe', ['filter' => 'auth:employe'], static function (RouteCollection $routes) {
	$routes->get('/', 'Employe\DashboardController::index');
	$routes->get('conges', 'Employe\CongeController::index');
	$routes->get('conges/creer', 'Employe\CongeController::create');
	$routes->post('conges/creer', 'Employe\CongeController::store');
	$routes->post('conges/(:num)/annuler', 'Employe\CongeController::annuler/$1');
	$routes->get('soldes', 'Employe\SoldeController::index');
	$routes->get('profil', 'Employe\ProfilController::index');
});

$routes->group('rh', ['filter' => 'auth:rh,admin'], static function (RouteCollection $routes) {
	$routes->get('/', 'RH\DemandeController::index');
	$routes->get('demandes', 'RH\DemandeController::index');
	$routes->post('demandes/(:num)/approuver', 'RH\DemandeController::approuver/$1');
	$routes->post('demandes/(:num)/refuser', 'RH\DemandeController::refuser/$1');
	$routes->get('soldes', 'RH\SoldeController::index');
});

$routes->group('admin', ['filter' => 'auth:admin'], static function (RouteCollection $routes) {
	$routes->get('/', 'Admin\DashboardController::index');
	$routes->get('dashboard', 'Admin\DashboardController::index');
	$routes->get('demandes', 'Admin\DemandeController::index');
	$routes->get('employes', 'Admin\EmployeController::index');
	$routes->post('employes/creer', 'Admin\EmployeController::create');
	$routes->get('employes/(:num)/editer', 'Admin\EmployeController::edit/$1');
	$routes->post('employes/(:num)/editer', 'Admin\EmployeController::update/$1');
	$routes->post('employes/(:num)/desactiver', 'Admin\EmployeController::deactivate/$1');

	$routes->get('departements', 'Admin\DepartementController::index');
	$routes->post('departements/creer', 'Admin\DepartementController::create');
	$routes->get('departements/(:num)/editer', 'Admin\DepartementController::edit/$1');
	$routes->post('departements/(:num)/editer', 'Admin\DepartementController::update/$1');
	$routes->post('departements/(:num)/supprimer', 'Admin\DepartementController::delete/$1');

	$routes->get('types-conge', 'Admin\TypeCongeController::index');
	$routes->post('types-conge/creer', 'Admin\TypeCongeController::create');
	$routes->get('types-conge/(:num)/editer', 'Admin\TypeCongeController::edit/$1');
	$routes->post('types-conge/(:num)/editer', 'Admin\TypeCongeController::update/$1');
});
