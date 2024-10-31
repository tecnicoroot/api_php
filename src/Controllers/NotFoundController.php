<?php 

namespace Tecnicoroot\ApiPhp\Controllers;

use Tecnicoroot\ApiPhp\Http\Request;
use Tecnicoroot\ApiPhp\Http\Response;

class NotFoundController
{
    public function index(Request $request, Response $response)
    {
        $response::json([
            'error'   => true,
            'success' => false,
            'message' => 'Sorry, route not found.'
        ], 404);
        return;
    }
}