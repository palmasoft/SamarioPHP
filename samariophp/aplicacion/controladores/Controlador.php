<?php
namespace SamarioPHP\Controladores;

class Controlador {

  // Atributos para almacenar datos globales
  protected $config;
  protected $BaseDatos;
  protected $logAplicacion;
  protected $logServidor;
  protected $logEventos;
  protected $aplicacion;
  protected $plantillas;

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
  }

  // Método para acceder a la configuración global
  public function obtenerConfig($clave) {
    return isset($this->config[$clave]) ? $this->config[$clave] : null;
  }

  // Método para manejar solicitudes
  public function manejarSolicitud() {
    // Puedes usar directamente los datos globales convertidos en propiedades de la clase
    echo "URL Base: " . $this->config['url_base'] . "<br>";
    echo "Conexión a la Base de Datos: " . ($this->db ? "Conectado" : "No conectado") . "<br>";
  }

  // Método de ejemplo para manejar la respuesta
  public function enviarRespuesta($mensaje) {
    // Aquí puedes usar los objetos de logs o el objeto de Slim para la respuesta
    $this->loggerAplicacion->info($mensaje);
    $this->aplicacion->response()->getBody()->write($mensaje);
  }

  //put your code here
  // Métodos comunes como renderizar vistas, redirección, etc.
  public function renderizar($vista, $datos = []) {
    include DIR_VISTAS . "{$vista}.php";
  }

  public function redirigir($url) {
    header("Location: {$url}");
    exit;
  }
}