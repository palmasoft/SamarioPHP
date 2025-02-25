<?php
return function ($peticion, $handler) use ($configuracion) {
  if ($configuracion['sistema']['mantenimiento']) {
    $loader = new \Twig\Loader\FilesystemLoader(DIR_VISTAS);
    $plantillas = new \Twig\Environment($loader);
    // Cambié la extensión a .html.php
    $contenido = $plantillas->render('mantenimiento.html.php', ['mensaje' => 'Vuelve pronto.']);

    $respuesta = new \Slim\Psr7\Response();
    $respuesta->getBody()->write($contenido);
    return $respuesta->withStatus(503);
  }

  return $handler->handle($peticion);
};
