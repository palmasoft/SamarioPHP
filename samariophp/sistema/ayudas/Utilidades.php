<?php
namespace SamarioPHP\Ayudas;

class Utilidades {

  public static function obtenerUltimoSegmento($cadena) {
    // Dividir la cadena por el separador '\'
    $partes = explode('\\', $cadena);

    // Obtener el último elemento del array resultante
    return end($partes);
  }

  public static function convertirNombreClaseATabla($nombreClase, $plural = true) {
    // Obtener el último segmento que corresponde a la entidad
    $nombreEntidad = self::obtenerUltimoSegmento($nombreClase);

    // Convertir la entidad a plural si es necesario
    if ($plural) {
      // Usamos una simple regla de pluralización (si el nombre termina en 's', no lo cambiamos)
      if (substr($nombreEntidad, -1) === 's') {
        $nombreTabla = $nombreEntidad; // Ya está en plural
      } else {
        $nombreTabla = $nombreEntidad . 's'; // Agregar 's' al final
      }
    } else {
      $nombreTabla = $nombreEntidad; // Devolver el nombre en singular
    }

    return $nombreTabla;
  }

  /**
   * Convierte un nombre de tabla a una clase en PascalCase, asegurándose de que todas las palabras sean singulares.
   * 
   * @param string $nombreTabla El nombre de la tabla en snake_case.
   * @param bool $singular Si debe convertirlo a singular (aunque esto se aplicará a todas las palabras).
   * @return string El nombre en formato PascalCase.
   */
  public static function convertirNombreTablaAClase($nombreTabla, $singular = false) {
    // Separar por guiones bajos y capitalizar cada palabra
    $palabras = explode('_', $nombreTabla);

    // Singularizar todas las palabras antes de capitalizarlas
    if ($singular) {
      $palabras = array_map(function ($palabra) {
        return self::singularizar($palabra); // Singulariza cada palabra
      }, $palabras);
    }

    // Capitalizar la primera letra de cada palabra y unirlas en PascalCase
    $palabras = array_map('ucfirst', $palabras);
    $nombreClase = implode('', $palabras);

    return $nombreClase;
  }

  /**
   * Singulariza un nombre plural en español.
   * 
   * @param string $palabra El nombre plural.
   * @return string El nombre en singular.
   */
  public static function singularizar($palabra) {
    // Regla para "s" al final
    if (preg_match('/[sS]$/', $palabra)) {
      // Si termina en "es", eliminamos "es"
      if (preg_match('/[eE]s$/', $palabra)) {
        return rtrim($palabra, 'es');
      }
      // Si termina en "s", eliminamos "s"
      return rtrim($palabra, 's');
    }

    // Reglas para "ces"
    if (preg_match('/[cC]es$/', $palabra)) {
      return rtrim($palabra, 'ces') . 'z';
    }

    // Reglas para sustantivos terminados en "iones"
    if (preg_match('/[iI]ones$/', $palabra)) {
      return rtrim($palabra, 'es');
    }

    // Reglas para "sión" y sus derivados
    if (preg_match('/[iI]sión$/', $palabra)) {
      return rtrim($palabra, 'sión') . 'sión';
    }

    // Si no aplica ninguna de las reglas anteriores, retornamos la palabra tal cual
    return $palabra;
  }

  // Función para generar el indicador completo (más de 14 caracteres si se desea)
  public static function generarIndicadorVersion() {
    $microtime = microtime(true); // Tiempo actual en segundos con microsegundos
    $fecha = date("YmdHis", (int) $microtime); // Fecha y hora estándar de 14 caracteres
    $microsegundos = (int) (($microtime - floor($microtime)) * 1000000); // Extraer microsegundos
    // Generar sufijo único con más dígitos para una versión larga
    $sufijoUnico = str_pad($microsegundos, 6, "0", STR_PAD_LEFT); // Usamos 6 dígitos

    return $fecha . $sufijoUnico; // Generar versión completa (20 caracteres)
  }

  // Función para generar el indicador de 14 caracteres (para Phinx)
  public static function generarIndicadorVersionPHINX() {
    $microtime = microtime(true); // Tiempo actual en segundos con microsegundos
    $fecha = date("YmdHi", (int) $microtime); // Fecha y hora estándar de 14 caracteres
    $microsegundos = (int) (($microtime - floor($microtime)) * 1000000); // Extraer microsegundos
    // Generar sufijo único de 4 dígitos para Phinx
    $sufijoUnico = str_pad($microsegundos % 100, 2, "0", STR_PAD_LEFT); // Usamos 4 dígitos para limitar a 14 caracteres

    return $fecha . $sufijoUnico; // Generar versión de exactamente 14 caracteres
  }

}