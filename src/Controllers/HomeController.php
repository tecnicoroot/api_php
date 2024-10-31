<?php

namespace Tecnicoroot\ApiPhp\Controllers;

use Tecnicoroot\ApiPhp\Http\Request;
use Tecnicoroot\ApiPhp\Http\Response;

class HomeController 
{
    
    public function index(Request $request, Response $response)
    {
        $response::json([
            'error'   => false,
            'success' => true,
            'data'    => 'API em funcionamento'
        ], 200);
    }
}