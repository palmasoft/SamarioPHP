<?php
namespace SamarioPHP\BaseDeDatos;
use Medoo\Medoo;

class Conexion extends Medoo {
  public function __construct($configuracion_global) {
    $config = require RUTA_CONFIG_MEEDO; // Ruta a tu archivo de configuración
    parent::__construct($config($configuracion_global));
  }

  // Función predefinida para consultas SELECT
  public function consultar($tabla, $condiciones = [], $columnas = '*') {
    return $this->select($tabla, $columnas, $condiciones);
  }

  // Función predefinida para insertar datos
  public function insertar($tabla, $datos) {
    return $this->insert($tabla, $datos);
  }

  // Función predefinida para actualizar datos
  public function actualizar($tabla, $datos, $condiciones) {
    return $this->update($tabla, $datos, $condiciones);
  }

  // Función predefinida para eliminar datos
  public function eliminar($tabla, $condiciones) {
    return $this->delete($tabla, $condiciones);
  }

  // Función predefinida para contar registros
  public function contar($tabla, $condiciones = []) {
    return $this->count($tabla, $condiciones);
  }
}