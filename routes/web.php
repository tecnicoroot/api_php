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

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    
    $router->get('/', function () {
        return microtime();
    });

    $router->post('/login', 'Auth\AuthController@login');

    $router->group(['middleware' => ['auth']], function () use ($router){
        $router->post('/logout', 'Auth\AuthController@logout');
        $router->post('/refresh', 'Auth\AuthController@refresh');
    });
    
    //Metodos que só podem ser acessados com o usuário autenticado
    $router->group(['prefix' => 'admin','middleware' => ['auth', 'roles:Administrador']], function () use ($router) {
        
        $router->get('/me', 'AuthController@me');
        #$router->post('/register', 'AuthController@register');

        //Rotas User
        $router->group(['prefix'=> 'user'], function () use ($router) {
            $router->post('/register', 'User\UserController@create');
            $router->put('/register/{id}', 'User\UserController@update');
            $router->put('/register/password/{id}', 'User\UserController@updatePassword'); 
            $router->get('/{id}', "User\UserController@findOneBy");
            $router->delete('/destroy/{id}', "User\UserController@delete");
        });


    });
    
   

});

