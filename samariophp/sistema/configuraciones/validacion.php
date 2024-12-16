<?php
/**
 * Validación de configuración crítica para evitar errores fatales.
 *
 * @param array $configuracion Configuración del sistema
 * @throws Exception Si alguna configuración crítica falta o es inválida
 */
return function ($configuracion) {
  $requeridos = ['base_de_datos', 'aplicacion'];
  foreach ($requeridos as $clave) {
    if (!isset($configuracion[$clave])) {
      throw new Exception("Falta configuración crítica: {$clave}");
    }
  }

  // Validar datos de la base de datos
  $dbConfig = ['tipo', 'nombre_basedatos', 'servidor', 'nombre_usuario', 'clave_usuario'];
  foreach ($dbConfig as $campo) {
    if (empty($configuracion['base_de_datos'][$campo])) {
      throw new Exception("Falta configuración de base de datos: {$campo}");
    }
  }
};
