<?php
use Psr\Http\Message\ResponseInterface as Respuesta;
use Psr\Http\Message\ServerRequestInterface as Peticion;
return function ($aplicacion, $BaseDeDatos, $logger) {
  $logger->info('[RUTAS DINÁMICAS] Cargando rutas dinámicas...');

//  $aplicacion->any('/{ruta:.+}', function (Peticion $peticion, Respuesta $respuesta, array $args) use ($BaseDeDatos, $logger) {
////    $rutaSolicitada = '/' . $args['ruta'];
////
////    // Consultar la base de datos para obtener controlador y operación
//////    $sql = "SELECT controlador, operacion FROM rutas WHERE ruta = :ruta LIMIT 1";
//////    $stmt = $baseDeDatos->prepare($sql);
//////    $stmt->bindParam(':ruta', $rutaSolicitada);
//////    $stmt->execute();
//////    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
////
//////    if ($resultado) {
//////      $controladorClase = "\\SamarioPHP\\Controladores\\" . $resultado['controlador'];
//////      $operacion = $resultado['operacion'];
//////
//////      if (class_exists($controladorClase)) {
//////        $controlador = new $controladorClase();
//////        if (method_exists($controlador, $operacion)) {
//////          return $controlador->$operacion($peticion, $respuesta);
//////        }
//////      }
//////      $logger->error("Método o controlador no encontrado para {$rutaSolicitada}");
//////      return $respuesta->withStatus(500)->getBody()->write("Error interno: método no encontrado.");
//////    }
////
////    $logger->warning("Ruta no encontrada: {$rutaSolicitada}");
////    return $respuesta->withStatus(404)->getBody()->write("Error 404: Ruta no encontrada.");
//  });
};
