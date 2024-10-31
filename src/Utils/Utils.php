<?php
    /**
     * Função responsável por facilitar a utilizazção print_r
     */
    function vd(mixed $var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
