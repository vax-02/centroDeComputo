<?php

use App\Http\Controllers\UserController;

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->post('/login', 'UserController@login');
$router->get('/materias', 'UserController@materias');//docente
$router->get('/materia', 'UserController@materia');//docente

$router->get('/materia/estudiantes', 'UserController@estudiantes');//docente
$router->get('/perfil', 'UserController@estudiante');//docente/materia/estudiante
$router->get('/estudiantes-grupo', 'UserController@estudiantesSeleccion');//materia/estudianteSeleecion


$router->get('/estudiantes/registro', 'UserController@registro');
