<?php
namespace SamarioPHP\Aplicacion\Modelos;

class Modelo {

  protected $tabla;
  protected $id;
  protected $datos = [];
  protected $conexion;

  public function __construct($id = null) {
    $this->conexion = $GLOBALS['datos']->conexion;
    $this->tabla = \Utilidades::convertirNombreClaseATabla(strtolower((new \ReflectionClass($this))->getShortName()), true);  // Tabla en plural
    $this->id = ($id ?? $this->id);
    if ($this->id) {
      $this->datos();
    }
  }

  public function __get($propiedad) {
    return $this->datos[$propiedad] ?? "no existe";
  }

  public function __set($propiedad, $valor) {
    $this->datos[$propiedad] = $valor;
  }

  public function rellenar(array $datos) {
    foreach ($datos as $clave => $valor) {
      $this->datos[$clave] = $valor;
    }
  }

  public function existe($id = null) {
    $this->id = ($id ?? $this->id);
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

  // Método para actualizar los datos del objeto después de guardar
  private function datos() {
    // Aquí puedes realizar una consulta a la base de datos para obtener los datos más recientes del usuario
    $datosActualizados = $this->conexion->get($this->tabla, '*', ['id' => $this->id]);

    // Asignar los datos actualizados al objeto
    if (!empty($datosActualizados)) {
      $this->datos = $datosActualizados;
    }
    return $this->datos;
  }

  // Relaciones

  public function tieneUn($modeloRelacionado, $claveForanea = null) {
    // Verificar si el ID del modelo actual está definido
    if (!isset($this->id)) {
      throw new \Exception("El modelo actual no tiene un ID definido.");
    }

    $OTROModelo = new $modeloRelacionado;
    $nombreModeloASOCIADO = (new \ReflectionClass(($OTROModelo)))->getShortName();
    $this->$nombreModeloASOCIADO = new \stdClass();
    $nombreModeloQUELLAMA = (new \ReflectionClass($this))->getShortName();

    if (is_null($claveForanea)) {
      $claveForanea = strtolower($nombreModeloQUELLAMA) . '_id';
    }
    $OTROModelo->$claveForanea = $this->id;
    // Consultar la base de datos para buscar el registro relacionado
    $OTROModelo->encontrarPor($claveForanea, $this->id);
    $this->$nombreModeloASOCIADO = $OTROModelo;

    return $OTROModelo;
  }

  protected function crearModelo($tablaRelacionada) {
    $nombreClaseModelo = ucfirst(rtrim($tablaRelacionada, 's')); // Convierte 'perfiles' a 'Perfil'
    $rutaModelo = "Modelos\\" . $nombreClaseModelo; // Asegúrate de que los modelos estén en este namespace

    if (class_exists($rutaModelo)) {
      return new $rutaModelo();
    }

    throw new \Exception("El modelo {$nombreClaseModelo} no existe.");
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

  public function encontrarPor($campo, $valor) {
    // Validar que los parámetros sean correctos
    if (empty($campo) || empty($valor)) {
      throw new \InvalidArgumentException("El campo y el valor son obligatorios para realizar la búsqueda.");
    }

    // Realizar la consulta a la base de datos
    $resultado = $this->conexion->get($this->tabla, '*', [$campo . "=" => $valor]);
    // Si no se encuentra el registro, lanzar una excepción
    if (!$resultado) {
      return null;
    }
    // Asignar los datos encontrados al modelo actual
    $this->id = $resultado['id'] ?? null;
    $this->rellenar($resultado);

    return $this; // Devuelve la instancia actual para encadenar métodos si es necesario
  }

  public function buscarPor($campo, $valor) {
    // Validar que los parámetros sean correctos
    if (empty($campo) || empty($valor)) {
      throw new \InvalidArgumentException("El campo y el valor son obligatorios para realizar la búsqueda.");
    }

    // Realizar la consulta a la base de datos
    $resultado = $this->conexion->get($this->tabla, '*', [$campo . " LIKE " => $valor]);

    // Si no se encuentra el registro, lanzar una excepción
    if (!$resultado) {
      throw new \RuntimeException("No se encontró ningún registro con {$campo} igual a {$valor} en la tabla {$this->tabla}.");
    }

    // Asignar los datos encontrados al modelo actual
    $this->id = $resultado['id'] ?? null;
    $this->rellenar($resultado);

    return $this; // Devuelve la instancia actual para encadenar métodos si es necesario
  }

// Método estático para buscar sin crear una instancia previa
  public static function buscarPorEstatico($campo, $valor) {
    // Crear una nueva instancia del modelo
    $clase = get_called_class();
    $instancia = new $clase();

    // Usar el método buscarPor en la instancia creada
    return $instancia->buscarPor($campo, $valor);
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
    return $GLOBALS['autenticacionServicio']->usuarioID ?? 0;
  }

}