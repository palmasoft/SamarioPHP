<?php
// Gestion de Rutas
use Slim\Routing\RouteCollectorProxy;
// Gestion de Peticiones y Respuestas HTTP//
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;

return function ($aplicacion) {

// Cargar rutas fijas
  $rutasFijas = require __DIR__ . '/rutas_fijas.php';
  $rutasFijas($aplicacion);

// Cargar rutas dinámicas
  $rutasDinamicas = require __DIR__ . '/rutas_dinamicas.php';
  $rutasDinamicas($aplicacion);
};
