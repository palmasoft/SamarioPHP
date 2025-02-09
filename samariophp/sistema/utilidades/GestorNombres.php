<?php

namespace SamarioPHP\Sistema\Utilidades;

class GestorNombres
{
    /**
     * Convierte un nombre de tabla en un nombre de clase en formato CamelCase.
     */
    public static function tablaAClase($nombreTabla)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $nombreTabla)));
    }

    /**
     * Convierte un nombre de clase en un nombre de tabla en formato snake_case.
     */
    public static function claseATabla($nombreClase)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $nombreClase));
    }

    /**
     * Valida si un nombre está en formato snake_case.
     */
    public static function esSnakeCase($nombre)
    {
        return preg_match('/^[a-z0-9_]+$/', $nombre);
    }

    /**
     * Genera un nombre único basado en un prefijo y un identificador.
     */
    public static function generarNombreUnico($prefijo, $id)
    {
        return $prefijo . '_' . uniqid($id . '_');
    }
}
