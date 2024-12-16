<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace SamarioPHP\Ayudas;

/**
 * Description of Archivos
 *
 * @author SISTEMAS
 */
class Archivos {
  //put your code here
  /**
   * Busca archivos que contienen una palabra clave en su nombre dentro de un directorio.
   *
   * @param string $directorio La ruta al directorio donde buscar.
   * @param string $palabraClave La palabra que debe contener el nombre del archivo.
   * @return array Los archivos encontrados que contienen la palabra clave.
   */
  static function buscarArchivosPorPalabra($directorio, $palabraClave) {
    // Obtener los archivos que contienen la palabra clave
    return glob($directorio . "/*$palabraClave*");
  }

  /**
   * Busca archivos que contienen una palabra clave en su nombre dentro de un directorio.
   *
   * @param string $directorio La ruta al directorio donde buscar.
   * @param string $palabraClave La palabra que debe contener el nombre del archivo.
   * @return array Los archivos encontrados que contienen la palabra clave.
   */
  static function buscarArchivosPorPalabraScandir($directorio, $palabraClave) {
    $archivos = scandir($directorio); // Obtener todos los archivos en el directorio
    $archivosEncontrados = [];

    foreach ($archivos as $archivo) {
      if (strpos($archivo, $palabraClave) !== false) {
        $archivosEncontrados[] = $archivo; // Agregar archivo si contiene la palabra clave
      }
    }

    return $archivosEncontrados;
  }
}