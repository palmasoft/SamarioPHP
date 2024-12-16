<?php
use Slim\Factory\AppFactory;
// Configurar Twig para las plantillas y vistas
$loader = new \Twig\Loader\FilesystemLoader(DIR_VISTAS);
$plantillas = new \Twig\Environment($loader, [
    'cache' => false, // O la ruta donde quieras guardar el caché
    ]);
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
