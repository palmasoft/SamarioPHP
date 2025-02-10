<?php
namespace SamarioPHP\Sistema\Utilidades;

trait GeneradorIdentificadores {

    public function generarNombreUsuario(string $base): string {
        return strtolower($this->normalizar_string($base)) . rand(100, 999);
    }

    /**
     * Genera un indicador de versión con fecha y microsegundos (20 caracteres).
     * 
     * @return string El indicador de versión.
     */
    public static function generarIndicadorVersion() {
        $microtime = microtime(true); // Tiempo actual en segundos con microsegundos
        $fecha = date("YmdHis", (int) $microtime); // Fecha y hora estándar de 14 caracteres
        $microsegundos = (int) (($microtime - floor($microtime)) * 1000000); // Extraer microsegundos
        // Generar sufijo único con más dígitos para una versión larga
        $sufijoUnico = str_pad($microsegundos, 6, "0", STR_PAD_LEFT); // Usamos 6 dígitos

        return $fecha . $sufijoUnico; // Generar versión completa (20 caracteres)
    }

    /**
     * Genera un indicador de versión de 14 caracteres (para Phinx).
     * 
     * @return string El indicador de versión de 14 caracteres.
     */
    public static function generarIndicadorVersionPHINX() {
        $microtime = microtime(true); // Tiempo actual en segundos con microsegundos
        $fecha = date("YmdHi", (int) $microtime); // Fecha y hora estándar de 14 caracteres
        $microsegundos = (int) (($microtime - floor($microtime)) * 1000000); // Extraer microsegundos
        // Generar sufijo único de 4 dígitos para Phinx
        $sufijoUnico = str_pad($microsegundos % 100, 2, "0", STR_PAD_LEFT); // Usamos 4 dígitos para limitar a 14 caracteres

        return $fecha . $sufijoUnico; // Generar versión de exactamente 14 caracteres
    }

}