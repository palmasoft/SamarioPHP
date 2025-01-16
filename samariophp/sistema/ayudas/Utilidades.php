<?php

class Utilidades {

  public static function convertirHtmlATexto($html) {
    // Quitar etiquetas HTML y decodificar caracteres especiales
    $textoPlano = strip_tags($html);
    $textoPlano = html_entity_decode($textoPlano, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Opcional: Formatear mejor las líneas (agregar saltos después de párrafos)
    $textoPlano = preg_replace('/\s+/', ' ', $textoPlano); // Eliminar espacios extra
    $textoPlano = preg_replace('/(\r?\n)+/', "\n", $textoPlano); // Normalizar saltos de línea

    return trim($textoPlano);
  }

  /**
   * Obtiene el último segmento de una cadena separada por '\' (barra invertida).
   * 
   * @param string $cadena La cadena con segmentos separados por '\'.
   * @return string El último segmento de la cadena.
   */
  public static function obtenerUltimoSegmento($cadena) {
    // Dividir la cadena por el separador '\'
    $partes = explode('\\', $cadena);

    // Obtener el último elemento del array resultante
    return end($partes);
  }

  /**
   * Convierte un nombre de clase en PascalCase a un nombre de tabla en snake_case.
   * 
   * @param string $nombreClase El nombre de la clase en PascalCase.
   * @param bool $plural Si debe convertirlo a plural (aunque esto se aplicará a todas las palabras).
   * @return string El nombre en formato snake_case.
   */
  public static function convertirNombreClaseATabla($nombreClase, $plural = false) {
    // Convertir PascalCase a snake_case
    $nombreTabla = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $nombreClase));

    // Convertir a plural si se solicita
    if ($plural) {
      $nombreTabla = self::pluralizar($nombreTabla);
    }

    return $nombreTabla;
  }

  /**
   * Pluraliza un nombre en singular (se aplica a cada palabra separada por '_').
   * 
   * @param string $palabra El nombre en singular.
   * @return string El nombre plural.
   */
  public static function pluralizar($palabra) {
    // Convertir cada palabra en el nombre a plural
    $palabras = explode('_', $palabra);
    $palabras = array_map(function ($palabra) {
      return self::hacerPlural($palabra);
    }, $palabras);

    return implode('_', $palabras);
  }

  /**
   * Convierte una palabra en singular a plural en español.
   * 
   * @param string $palabra La palabra en singular.
   * @return string La palabra en plural.
   */
  public static function hacerPlural($palabra) {
    // Si termina en 'z', se convierte a 'ces' (para algunos sustantivos)
    if (preg_match('/[zZ]$/', $palabra)) {
      return $palabra . 'es';
    }

    // Si termina en 's', no es necesario pluralizar
    if (preg_match('/[sS]$/', $palabra)) {
      return $palabra;
    }

    // Si termina en vocal, agregamos 's'
    if (preg_match('/[aeiouáéíóúAEIOUÁÉÍÓÚ]$/', $palabra)) {
      return $palabra . 's';
    }

    // Si termina en consonante, agregamos 'es'
    return $palabra . 'es';
  }

  /**
   * Convierte un nombre de tabla a una clase en PascalCase, asegurándose de que todas las palabras sean singulares.
   * 
   * @param string $nombreTabla El nombre de la tabla en snake_case.
   * @param bool $singular Si debe convertirlo a singular (aunque esto se aplicará a todas las palabras).
   * @return string El nombre en formato PascalCase.
   */
  public static function convertirNombreTablaAClase($nombreTabla, $singular = false) {
    // Separar por guiones bajos
    $palabras = explode('_', $nombreTabla);

    // Singularizar todas las palabras antes de capitalizarlas si se solicita
    if ($singular) {
      $palabras = array_map(function ($palabra) {
        return self::singularizar($palabra); // Singulariza cada palabra
      }, $palabras);
    }

    // Capitalizar la primera letra de cada palabra y unirlas en PascalCase
    $nombreClase = implode('', array_map('ucfirst', $palabras));

    return $nombreClase;
  }

  /**
   * Singulariza un nombre plural en español.
   * 
   * @param string $palabra El nombre plural.
   * @return string El nombre en singular.
   */
  public static function singularizar($palabra) {
    // Reglas para pluralización
    if (preg_match('/[sS]$/', $palabra)) {
      // Si termina en "es", eliminamos "es"
      if (preg_match('/[eE]s$/', $palabra)) {
        return rtrim($palabra, 'es');
      }
      // Si termina en "s", eliminamos "s"
      return rtrim($palabra, 's');
    }

    // Reglas para sustantivos terminados en "ces"
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

  /**
   * Genera un nombre de usuario a partir de un nombre completo.
   * 
   * @param string $nombreCompleto El nombre completo del usuario.
   * @return string El nombre de usuario generado.
   */
  public static function generarNombreUsuario($nombreCompleto) {
    // Eliminar acentos y caracteres especiales
    $nombreCompleto = self::normalizar_string($nombreCompleto);

    // Convertir a minúsculas
    $nombreCompleto = strtolower($nombreCompleto);

    // Reemplazar espacios con guiones bajos
    $nombreUsuario = str_replace(' ', '_', $nombreCompleto);

    // Limitar la longitud del nombre de usuario (opcional)
    $nombreUsuario = substr($nombreUsuario, 0, 21); // Limitar a 21 caracteres

    return $nombreUsuario;
  }

  /**
   * Normaliza un string, eliminando los acentos y caracteres especiales.
   * 
   * @param string $string El string a normalizar.
   * @return string El string normalizado.
   */
  public static function normalizar_string($string) {
    // Reemplazar caracteres especiales y acentos por equivalentes sin acentos
    $string = preg_replace(
        ['/á/', '/é/', '/í/', '/ó/', '/ú/', '/ñ/', '/Á/', '/É/', '/Í/', '/Ó/', '/Ú/', '/Ñ/'],
        ['a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N'],
        $string
    );

    return $string;
  }

}