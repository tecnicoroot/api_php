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

$router->get('/', function () {
    
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/', function () {
        return microtime();
    });

    
    $router->post('/login', 'AuthController@login');
    //Metodo que só pode ser acessado com o usuário autenticado
    $router->group(['prefix' => 'admin','middleware' => ['auth', 'roles:Admin']], function () use ($router) {
        $router->get('/me', 'AuthController@me');
        $router->post('/register', 'AuthController@register');

    });
    
});
//Rota de texte; 
//$router->get('/send', 'EmailController@send');