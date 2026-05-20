<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('login', 'AuthController::home');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// Groupe employé
$routes->group('employe', ['filter' => 'auth:employe'], function ($routes) {
    $routes->get('/', 'EmployeController::dashboard');
    $routes->get('dashboard', 'EmployeController::dashboard');
    $routes->get('mes-conges', 'EmployeController::index');
    $routes->get('nouveau-conge', 'EmployeController::create');
    $routes->post('nouveau-conge', 'EmployeController::store');
    $routes->post('conges/annuler/(:num)', 'EmployeController::cancel/$1');
    $routes->get('profil', 'EmployeController::profil');
    $routes->post('profil', 'EmployeController::updateProfil');
});

// Groupe RH
$routes->group('rh', ['filter' => 'auth:rh,admin'], function ($routes) {
    $routes->get('/', 'RhController::index');
    $routes->post('approve/(:num)', 'RhController::approve/$1');
    $routes->post('refuse/(:num)', 'RhController::refuse/$1');
});

// Groupe admin
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/', 'AdminController::index');

    // Employés
    $routes->get('employes', 'AdminController::employes');
    $routes->post('employes', 'AdminController::storeEmploye');
    $routes->post('employes/toggle/(:num)', 'AdminController::toggleEmploye/$1');
    $routes->post('employes/update/(:num)', 'AdminController::updateEmploye/$1');

    // Départements
    $routes->get('departements', 'AdminController::departements');
    $routes->post('departements', 'AdminController::storeDepartement');
    $routes->post('departements/delete/(:num)', 'AdminController::deleteDepartement/$1');

    // Types de congé
    $routes->get('types-conge', 'AdminController::typesConges');
    $routes->post('types-conge', 'AdminController::storeTypeConge');
    $routes->post('types-conge/delete/(:num)', 'AdminController::deleteTypeConge/$1');

    // Soldes
    $routes->get('soldes', 'AdminController::soldes');
    $routes->post('soldes/update/(:num)', 'AdminController::updateSolde/$1');
});
