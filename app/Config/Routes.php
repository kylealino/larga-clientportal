<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->add('mylogin-auth', 'MyLogIn::auth');
$routes->add('mylogout', 'MyLogIn::logout');
$routes->get('mydashboard', 'MyDashboard::index',['filter' => 'myauthuser']);
$routes->get('client-portal', 'ClientPortal::index');  // JUST ADD THIS ONE LINE

$routes->get('myregistration', 'MyRegistration::index');
$routes->post('myregistration', 'MyRegistration::index');

$routes->get('myotp', 'MyOTP::index');
$routes->post('myotp', 'MyOTP::index');