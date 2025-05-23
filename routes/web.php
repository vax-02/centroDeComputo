<?php

use App\Http\Controllers\UserController;

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
$router->post('/login', 'UserController@login');
$router->get('/docente/materias', 'UserController@materias');
$router->get('/docente/materia', 'UserController@materia');

$router->get('/docente/materia/estudiantes', 'UserController@estudiantes');
$router->get('/docente/materia/estudiante', 'UserController@estudiante');
$router->get('/docente/materia/estudiantesSeleccion', 'UserController@estudiantesSeleccion');


$router->get('/estudiantes/registro', 'UserController@registro');
