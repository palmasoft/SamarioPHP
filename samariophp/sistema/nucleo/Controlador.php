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
  protected $sesion;
  protected $correos;
  protected $respuesta;
  protected $datos;

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
    $this->sesion = $GLOBALS['autenticacionServicio'];
    $this->correos = $GLOBALS['correoElectronicoServicio'];
    // Cargar datos de \GestorHTTP::$datos y convertirlos en propiedades de la clase
    $this->cargarDatos(\GestorHTTP::$datos);
  }

  // Método para cargar los datos y convertirlos en propiedades
  private function cargarDatos($datos = null) {
    if (!is_null($datos)) {
      foreach ($datos as $key => $value) {
        // Asignar los datos al array interno
        $this->datos[$key] = $value;
      }
    }
  }

  // Método mágico __get para acceder a los datos como si fueran propiedades
  public function __get($name) {
    // Devuelve el valor si existe, si no, retorna null
    return isset($this->datos[$name]) ? $this->datos[$name] : null;
  }

  // Método mágico __set para asignar valores a los datos como si fueran propiedades
  public function __set($name, $value) {
    // Asigna el valor al array interno
    $this->datos[$name] = $value;
  }

  // Método para mostrar todos los datos
  public function mostrarDatos() {
    print_r($this->datos);
  }

// Método para verificar si existe un dato específico
  public function tieneDato($name) {
    return isset($this->$name); // Verifica si la propiedad existe
  }

  // Método para verificar si múltiples datos existen
  public function tieneDatos(array $keys) {
    // Verifica si todos los datos existen usando isset()
    foreach ($keys as $key) {
      if (!isset($this->$key)) {
        return false;  // Si alguna propiedad no existe, retorna false
      }
    }
    return true;  // Todos los datos existen
  }

  //Metodo para acceder a la configuraciÃ³n global
  public function obtenerConfig($clave) {
    return $this->config[$clave] ?? null;
  }

  //Metodo de ejemplo para manejar la solicitud
  public function manejarSolicitud() {
    $urlBase = $this->obtenerConfig('url_base');
    $this->logAplicacion->info("URL Base: {$urlBase}");
    return $this->respuesta->withHeader('Content-Type', 'text/plain')
            ->withBody("URL Base: {$urlBase}");
  }

  //Metodo de ejemplo para manejar la respuesta
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