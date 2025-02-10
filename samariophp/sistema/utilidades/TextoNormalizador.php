<?php

namespace SamarioPHP\Sistema\Utilidades;

trait TextoNormalizador
{
    public static function normalizar_string(string $texto): string
    {
        $texto = strtolower($texto);
        $texto = preg_replace('/[áàâä]/u', 'a', $texto);
        $texto = preg_replace('/[éèêë]/u', 'e', $texto);
        $texto = preg_replace('/[íìîï]/u', 'i', $texto);
        $texto = preg_replace('/[óòôö]/u', 'o', $texto);
        $texto = preg_replace('/[úùûü]/u', 'u', $texto);
        $texto = preg_replace('/[^a-z0-9_]/u', '_', $texto);
        return preg_replace('/_+/', '_', $texto);
    }
}
