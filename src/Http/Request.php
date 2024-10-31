<?php

namespace Tecnicoroot\ApiPhp\Http;

class Request
{
    /**
     * Função responsável por retornar o metodo da rota.
     */
    public static function method()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public static function body()
    {
        $json = json_decode(file_get_contents('php://input'), true) ?? [];

        $data = match(self::method()) {
            'GET' => $_GET,
            'POST', 'PUT', 'DELETE' => $json,
        };

        return $data;
    }

}
