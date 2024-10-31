<?php

use Tecnicoroot\ApiPhp\Http\Route;

/**
 * Primeira rota da api
 */
Route::get('/', 'HomeController@index');