<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/vendor', 'VendorController@index');
$router->get('/vendor/{id}', 'VendorController@show');
$router->post('/vendor', 'VendorController@create');
$router->put('/vendor/{id}', 'VendorController@update');
$router->delete('/vendor/{id}', 'VendorController@delete');

$router->get('/sale', 'SaleController@index');
$router->get('/sale/{id}', 'SaleController@show');
$router->get('/sale/vendor/{id}', 'SaleController@showByVendor');
$router->post('/sale', 'SaleController@create');
$router->put('/sale/{id}', 'SaleController@update');
$router->delete('/sale/{id}', 'SaleController@delete');
