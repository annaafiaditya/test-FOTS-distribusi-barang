<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Tracking::index');
$routes->get('staff/login', 'StaffAuth::login');
$routes->post('staff/login', 'StaffAuth::login', ['filter' => 'csrf']);
$routes->get('staff/register', 'StaffAuth::register');
$routes->post('staff/register', 'StaffAuth::register', ['filter' => 'csrf']);
$routes->post('staff/logout', 'StaffAuth::logout', ['filter' => 'csrf']);

$routes->group('staff', ['filter' => 'staffAuth'], static function ($routes): void {
    $routes->get('/', 'Staff::index');
    $routes->get('create', 'Staff::create');
    $routes->post('create', 'Staff::create', ['filter' => 'csrf']);
    $routes->post('update-location/(:num)', 'Staff::updateLocation/$1', ['filter' => 'csrf']);
    $routes->post('complete/(:num)', 'Staff::completeShipment/$1', ['filter' => 'csrf']);
    $routes->post('delete/(:num)', 'Staff::deleteShipment/$1', ['filter' => 'csrf']);
    $routes->post('restore/(:num)', 'Staff::restoreShipment/$1', ['filter' => 'csrf']);
    $routes->post('delete-permanently/(:num)', 'Staff::deleteShipmentPermanently/$1', ['filter' => 'csrf']);
    $routes->get('guide', 'Staff::guide');
    $routes->post('guide/complete', 'Staff::completeGuide', ['filter' => 'csrf']);
});
