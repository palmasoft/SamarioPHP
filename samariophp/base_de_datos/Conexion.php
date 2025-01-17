<?php
namespace SamarioPHP\BaseDeDatos;

use Medoo\Medoo;

class Conexion extends Medoo {

  private $tabla;
  private $condiciones = [];
  private $orden = [];
  private $limite = [];
  private $grupo = [];
  private $joins = [];

  public function __construct($config) {
    parent::__construct($config);
  }

  // Seleccionar tabla principal
  public function tabla($nombre) {
    $this->tabla = $nombre;
    $this->resetConfiguraciones();
    return $this;
  }

  // Agregar condiciones WHERE
  public function donde(array $condiciones) {
    $this->condiciones = $condiciones;
    return $this;
  }

  // Agregar orden ORDER BY
  public function ordenadoPor(array $orden) {
    $this->orden = $orden;
    return $this;
  }

  // Limitar resultados LIMIT
  public function limitado(array $limite) {
    $this->limite = $limite;
    return $this;
  }

  // Agregar agrupamiento GROUP BY
  public function agrupadoPor(array $grupo) {
    $this->grupo = $grupo;
    return $this;
  }

  // Agregar uniones JOIN
  public function unirCon(array $joins) {
    $this->joins = $joins;
    return $this;
  }

  // Ejecutar SELECT
  public function seleccionar($columnas = '*') {
    $opciones = [];

    if (!empty($this->joins)) {
      foreach ($this->joins as [$tabla, $condicion, $tipo]) {
        $opciones['JOIN'][$tipo][$tabla] = $condicion;
      }
    }

    if (!empty($this->condiciones)) {
      $opciones['WHERE'] = $this->condiciones;
    }
    if (!empty($this->orden)) {
      $opciones['ORDER'] = $this->orden;
    }
    if (!empty($this->limite)) {
      $opciones['LIMIT'] = $this->limite;
    }
    if (!empty($this->grupo)) {
      $opciones['GROUP'] = $this->grupo;
    }

    $resultados = $this->select(
        $this->tabla,
        $columnas,
        $this->condiciones ?? null
    );

    $this->resetConfiguraciones(); // Limpiar configuraciones después de la consulta
    return $resultados;
  }

  // Consultas directas SQL
  public function consultarSQL($consulta) {
    return $this->query($consulta)->fetchAll();
  }

  // Contar registros
  public function contar($condiciones = []) {
    return $this->count($this->tabla, $condiciones ?: $this->condiciones);
  }

  // Insertar datos
  public function insertar($tabla, $datos) {
    return $this->insert($tabla, $datos);
  }

  // Actualizar datos
  public function actualizar($tabla, $datos, $condiciones) {
    return $this->update($tabla, $datos, $condiciones);
  }

  // Eliminar datos
  public function eliminar($tabla, $condiciones) {
    return $this->delete($tabla, $condiciones);
  }

  // Reiniciar configuraciones internas
  private function resetConfiguraciones() {
    $this->condiciones = [];
    $this->orden = [];
    $this->limite = [];
    $this->grupo = [];
    $this->joins = [];
  }

}