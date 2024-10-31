<?php

namespace Tecnicoroot\ApiPhp\Core;

use Tecnicoroot\ApiPhp\Http\Request;
use Tecnicoroot\ApiPhp\Http\Response;

class Core
{
    /**
     * Função responsável por tratar as solicitações das rotas
     * @param array $routes
     */
    public static function dispatch(array $routes)
    {
        $path = '/';

        isset($_GET['path']) && $path .= $_GET['path'];

        $path !== '/' && $path = rtrim($path, '/');

        $prefixController = 'Tecnicoroot\\ApiPhp\\Controllers\\';

        $routeFound = false;
        foreach ($routes as $route) {
            $pattern = '#^'. preg_replace('/{id}/', '([\w-]+)', $route['path']) .'$#';

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);

                $routeFound = true;

                if ($route['method'] !== Request::method()) {
                    Response::json([
                        'error'   => true,
                        'success' => false,
                        'message' => 'Sorry, method not allowed.'
                    ], 405);
                    return;
                }

                [$controller, $action] = explode('@', $route['action']);

                $controller = $prefixController . $controller;
                $extendController = new $controller();
                $extendController->$action(new Request, new Response, $matches);
            }
        }

        if (!$routeFound) {
            $controller = $prefixController . 'NotFoundController';
            $extendController = new $controller();
            $extendController->index(new Request, new Response);
        }
    }
}
