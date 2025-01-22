<?php

class GestorRutas {

  private $rutasFijas = [];
  private $db;

  public function __construct(PDO $conexion) {
    $this->db = $conexion;
  }

  function obtenerRuta($uri, $metodo) {
    return $this->db->query(
            "SELECT * FROM permisos WHERE ruta = :ruta AND metodo = :metodo LIMIT 1",
            ['ruta' => $uri, 'metodo' => $metodo]
    );
  }

  function obtenerControlador($nombreControlador) {
    $clase = "\\App\\Controladores\\{$nombreControlador}";
    if (!class_exists($clase)) {
      throw new Exception("El controlador {$nombreControlador} no existe.");
    }
    return new $clase();
  }

  public function resolverRuta($uri) {
    // Primero verifica si la ruta es fija
    if (array_key_exists($uri, $this->rutasFijas)) {
      return $this->ejecutarRuta($this->rutasFijas[$uri]);
    }

    // Si no es fija, busca en la base de datos
    return $this->resolverRutaDinamica($uri);
  }

  private function resolverRutaDinamica($uri) {
    // Consulta a la base de datos para obtener controlador y operación
    $sql = "SELECT controlador, operacion FROM rutas WHERE ruta = :ruta LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':ruta', $uri);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
      return $this->ejecutarRuta($resultado);
    }

    // Si no existe la ruta, devuelve un error 404
    http_response_code(404);
    echo "Error 404: Ruta no encontrada.";
  }

  private function ejecutarRuta($config) {
    $controladorNombre = "\\Controladores\\" . $config['controlador'];
    $operacion = $config['operacion'];

    if (class_exists($controladorNombre)) {
      $controlador = new $controladorNombre();

      if (method_exists($controlador, $operacion)) {
        return $controlador->$operacion();
      }
    }

    // Error si no existe controlador u operación
    http_response_code(500);
    echo "Error 500: No se pudo ejecutar la operación.";
  }

}