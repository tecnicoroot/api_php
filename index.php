<?php

use Tecnicoroot\ApiPhp\Core\Core;
use Tecnicoroot\ApiPhp\Http\Route;

// Carregamento do auto load
require_once 'vendor/autoload.php';
// Carregamento de outros arquivos de configuração
//require_once 'src/Utils/Utils.php';
//require_once 'src/routes/v1/routesv1.php';
// Os arquivos acima estão na configuração do composer.json

//vd($_REQUEST);

Core::dispatch((Route::routes()));
