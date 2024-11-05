<?php

use Tecnicoroot\ApiPhp\Core\Core;
use Tecnicoroot\ApiPhp\Core\Middlewares\Maintenance;
use Tecnicoroot\ApiPhp\Http\Route;
use Tecnicoroot\ApiPhp\Core\Middlewares\Queue as MiddlewareQueue;

// Carregamento do auto load
require_once 'vendor/autoload.php';
// Carregamento de outros arquivos de configuração
//require_once 'src/Utils/Utils.php';
//require_once 'src/routes/v1/routesv1.php';
// Os arquivos acima estão na configuração do composer.json

//vd($_REQUEST);


// Definindo o mapeamento de middlewares
MiddlewareQueue::setMap([
    'maintenance' => Maintenance::class
]);

// Configura os middlewares padrões
MiddlewareQueue::setDefaultMiddlewares([
    'maintenance'
]);

Core::dispatch((Route::routes()));
