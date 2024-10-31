<?php

namespace Tecnicoroot\ApiPhp\Http;

class Route
{
    /**
     * $routes é a variável que receberá as rotas cadastradas
     */
    private static array $routes = [];

    /**
     * Retrona as rotas do tipo GET
     * @param string $path
     * @param string $action
     */
    public static function get(string $path, string $action)
    {
        self::$routes[] = [
            'path'   => $path,
            'action' => $action,
            'method' => 'GET'
        ];
    }

    /**
     * Retrona as rotas do tipo POST
     * @param string $path
     * @param string $action
     */
    public static function post(string $path, string $action)
    {
        self::$routes[] = [
            'path'   => $path,
            'action' => $action,
            'method' => 'POST'
        ];
    }

    /**
     * Retrona as rotas do tipo PUT
     * @param string $path
     * @param string $action
     */
    public static function put(string $path, string $action)
    {
        self::$routes[] = [
            'path'   => $path,
            'action' => $action,
            'method' => 'PUT'
        ];
    }

    /**
     * Retrona as rotas do tipo DELETE
     * @param string $path
     * @param string $action
     */
    public static function delete(string $path, string $action)
    {
        self::$routes[] = [
            'path'   => $path,
            'action' => $action,
            'method' => 'DELETE'
        ];
    }

    /**
     * Retrona as rotas
     * @return array
     */
    public static function routes(): array
    {
        return self::$routes;
    }
}