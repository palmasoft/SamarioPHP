<?php
use Psr\Http\Message\ResponseInterface as Respuesta;
use Psr\Http\Message\ServerRequestInterface as Peticion;

return function ($aplicacion, $BaseDeDatos, $logger) {
  $logger->info('[RUTAS DINÁMICAS] Cargando rutas dinámicas...');
//
    
  $aplicacion->any('/{ruta:.+}', function (Peticion $peticion, Respuesta $respuesta, array $args) {

    $rutaSolicitada = '/' . $args['ruta'];
//    
//////
//    if ($rutaSolicitada == '/usuarios/nuevo') {
//      $contenido = $GLOBALS['plantillas']->render(VISTA_404, [
//          'codigo_error' => 404,
//          'mensaje_error' => 'La página que buscas no existe.',
//      ]);
//      $respuesta->getBody()->write($contenido);
//      return $respuesta->withStatus(404);
//    }
    return $respuesta;
  });
  
  
};
