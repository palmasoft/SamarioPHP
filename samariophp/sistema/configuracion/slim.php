<?php
use Slim\Factory\AppFactory;

/**
 * Configuración de Slim para el framework.
 *
 * @param array $configuracion Configuración general
 * @param \Twig\Environment $plantillas Entorno de Twig
 * @return \Slim\App Aplicación Slim configurada
 */
return function ($configuracion, $plantillas) {
  // Preparar la aplicación Slim
  $aplicacion = AppFactory::create();
  $aplicacion->addRoutingMiddleware();
  $aplicacion->addErrorMiddleware(true, true, true)
      ->setErrorHandler(Slim\Exception\HttpNotFoundException::class, function ($peticion, $handler) use ($plantillas) {
        $respuesta = new \Slim\Psr7\Response();
        $contenido = $plantillas->render(VISTA_404);
        $respuesta->getBody()->write($contenido);
        return $respuesta->withStatus(404);
      });

  return $aplicacion;
};
