<?php

namespace SamarioPHP\Sistema\Utilidades;

trait GeneradorIdentificadores
{
    public function generarIndicadorVersion(): string
    {
        return date('YmdHis');
    }

    public function generarIndicadorVersionPHINX(): string
    {
        return date('YmdHis') . '_' . uniqid();
    }

    public function generarNombreUsuario(string $base): string
    {
        return strtolower($this->normalizar_string($base)) . rand(100, 999);
    }
}
