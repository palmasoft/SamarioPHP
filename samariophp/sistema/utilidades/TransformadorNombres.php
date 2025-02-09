<?php

namespace SamarioPHP\Sistema\Utilidades;

trait TransformadorNombres
{
    public function convertirNombreClaseATabla(string $nombreClase): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $nombreClase));
    }

    public function convertirNombreTablaAClase(string $nombreTabla): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $nombreTabla)));
    }

    public function pluralizar(string $nombre): string
    {
        return $nombre . 's'; // Aquí puedes mejorar esta lógica para reglas más complejas en español.
    }

    public function singularizar(string $nombre): string
    {
        return rtrim($nombre, 's');
    }
}
