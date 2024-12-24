<?php
namespace SamarioPHP\Aplicacion\Controladores;

use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;

class Controlador {

  // Atributos para almacenar datos globales
  protected $config;
  protected $BaseDatos;
  protected $logAplicacion;
  protected $logServidor;
  protected $logEventos;
  protected $aplicacion;
  protected $plantillas;
  protected $sesionControlador;
  protected $respuesta;

  // Constructor donde se cargan los datos globales 
  public function __construct() {
    // Acceder a los datos globales definidos en $GLOBALS
    $this->config = $GLOBALS['configuracion'];
    $this->BaseDatos = $GLOBALS['datos'];
    $this->logAplicacion = $GLOBALS['loggers']['aplicacion'];
    $this->logServidor = $GLOBALS['loggers']['servidor'];
    $this->logEventos = $GLOBALS['loggers']['eventos'];
    $this->aplicacion = $GLOBALS['aplicacion'];
    $this->plantillas = $GLOBALS['plantillas'];
//    $this->respuesta = $this->aplicacion->response();
    $this->sesionControlador = new SesionControlador();
  }

  // MÃ©todo para acceder a la configuraciÃ³n global
  public function obtenerConfig($clave) {
    return $this->config[$clave] ?? null;
  }

  // MÃ©todo de ejemplo para manejar la solicitud
  public function manejarSolicitud() {
    $urlBase = $this->obtenerConfig('url_base');
    $this->logAplicacion->info("URL Base: {$urlBase}");
    return $this->respuesta->withHeader('Content-Type', 'text/plain')
            ->withBody("URL Base: {$urlBase}");
  }

  // MÃ©todo de ejemplo para manejar la respuesta
  public function enviarRespuesta($mensaje) {
    // AquÃ­ puedes usar los objetos de logs o el objeto de Slim para la respuesta
    $this->loggerAplicacion->info($mensaje);
    $this->aplicacion->response()->getBody()->write($mensaje);
  }

  // Renderizar vistas
  public function renderizar(string $vista, array $datos = []): HTTPRespuesta {
    $this->respuesta = \GestorHTTP::obtenerRespuesta();
    $archivo_vista = $vista . '.html.php';
    if (!file_exists(DIR_VISTAS . $archivo_vista)) {
      throw new \Exception("La vista '{$archivo_vista}' no existe.");
    }
    $html = $this->plantillas->render($archivo_vista, $datos);
    $this->respuesta->getBody()->write($html);
    return $this->respuesta;
  }

  // Redirigir a una URL
  public function redirigir(string $url): HTTPRespuesta {
    return $this->respuesta->withHeader('Location', $url)->withStatus(302);
  }

}