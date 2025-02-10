<?php
use Psr\Http\Message\ResponseInterface as Respuesta;
use Psr\Http\Message\ServerRequestInterface as Peticion;

return function ($aplicacion) {
    
    
     // Middleware para manejar rutas dinámicas
  $aplicacion->any('/{modulo}/{accion}[/{parametros:.*}]', function (HTTPSolicitud $peticion, HTTPRespuesta $respuesta, array $args) {
    $modulo = $args['modulo'];
    $archivoRutas = __DIR__ . "/modulos/{$modulo}/rutas.php"; // Ruta del archivo de rutas del módulo
    echo "any carto";
    print_r($archivoRutas);

//    if (file_exists($archivoRutas)) {
//      $rutasModulo = require $archivoRutas;
//      $rutasModulo($this); // Cargar las rutas del módulo actual
//    } else {
//      // Si no existe el archivo de rutas del módulo, retornar 404
//      $respuesta->getBody()->write("El módulo solicitado '{$modulo}' no tiene rutas definidas.");
//      return $respuesta->withStatus(404);
//    }
//
//    return $respuesta;
  });
  

  $aplicacion->any('/{ruta:.+}', function (Peticion $peticion, Respuesta $respuesta, array $args) {

    $rutaSolicitada = '/' . $args['ruta'];
    echo "any largo";
    print_r($rutaSolicitada);
//    
//////
//    if ($rutaSolicitada == '/usuarios/nuevo') {
//      $contenido = $GLOBALS['plantillas']->render(VISTA_404, [
//          'codigo_error' => 404,
//          'mensaje_error' => 'La página que buscas no existe.',
//      ]);
      //$respuesta->getBody()->write($rutaSolicitada);
      //return $respuesta->withStatus(404);
//    }
    return $respuesta;
  });
};
