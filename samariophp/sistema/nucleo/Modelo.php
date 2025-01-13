<?php
namespace SamarioPHP\Aplicacion\Modelos;

class Modelo {

  protected $tabla;
  protected $id;
  protected $datos = [];
  protected $conexion;

  public function __construct($id = null) {
    $this->conexion = $GLOBALS['datos']->conexion;
    $this->id = $id;
    $this->tabla = \SamarioPHP\Ayudas\Utilidades::convertirNombreClaseATabla(strtolower(get_class($this)));  // Tabla en plural

    print_r($this);

    if ($id) {
      $registro = $this->conexion->get($this->tabla, "*", ["id" => $id]);
      if ($registro) {
        $this->datos = $registro;
      }
    }
  }

  public function __get($propiedad) {
    return $this->datos[$propiedad] ?? null;
  }

  public function __set($propiedad, $valor) {
    $this->datos[$propiedad] = $valor;
  }

  public function rellenar(array $datos) {
    foreach ($datos as $clave => $valor) {
      $this->datos[$clave] = $valor;
    }
  }

  public function existe() {
    return isset($this->id) && $this->conexion->has($this->tabla, ["id" => $this->id]);
  }

  public function guardar() {
    $usuarioId = self::obtenerUsuarioActual();

    if ($this->existe()) {
      $this->datos['modificado_por'] = $usuarioId;
      $this->conexion->update($this->tabla, $this->datos, ["id" => $this->id]);
    } else {
      $this->datos['creado_por'] = $usuarioId;
      $this->conexion->insert($this->tabla, $this->datos);
      $this->id = $this->conexion->id();
    }
  }

  public function borrar() {
    if ($this->existe()) {
      $this->datos['eliminado_por'] = self::obtenerUsuarioActual();
      $this->datos['fecha_eliminacion'] = date('Y-m-d H:i:s');
      $this->conexion->update($this->tabla, $this->datos, ["id" => $this->id]);
    }
  }

  // Relaciones
  public function tieneUn($tablaRel, $claveForanea) {
    return $this->conexion->get($tablaRel, "*", [$claveForanea => $this->id]);
  }

  public function tieneMuchos($tablaRel, $claveForanea) {
    return $this->conexion->select($tablaRel, "*", [$claveForanea => $this->id]);
  }

  public function perteneceAUn($tablaRel, $clavePrimaria) {
    return $this->conexion->get($tablaRel, "*", ["id" => $this->datos[$clavePrimaria]]);
  }

  public function perteneceAMuchos($tablaRel, $clavePrimaria) {
    return $this->conexion->select($tablaRel, "*", ["id" => $this->datos[$clavePrimaria]]);
  }

  public static function todos() {
    $clase = get_called_class();
    $instancia = new $clase();
    return $instancia->conexion->select($instancia->tabla, "*");
  }

  public static function donde($campo, $operador, $valor) {
    $clase = get_called_class();
    $instancia = new $clase();
    return $instancia->conexion->select($instancia->tabla, "*", [
            $campo . " " . $operador => $valor
    ]);
  }

  public static function ordenadoPor($campo, $direccion = "ASC") {
    $clase = get_called_class();
    $instancia = new $clase();
    return $instancia->conexion->select($instancia->tabla, "*", [
            "ORDER" => [$campo => $direccion]
    ]);
  }

  // Obtener el usuario actual (ajústalo a tu sistema de autenticación)
  private static function obtenerUsuarioActual() {
    return $GLOBALS['autenticacionServicio']->usuarioID ?? null;
  }

}