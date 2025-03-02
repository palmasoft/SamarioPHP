<?php
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GestorHTTP {

// Propiedades estáticas
  public static $Solicitud;
  public static $Respuesta;
  public static $datos;

// Métodos adicionales que necesites
// Métodos adicionales que necesites
  public static function solicitud($Solicitud) {
    // Guardamos la solicitud
    self::$Solicitud = $Solicitud;

    // Inicializamos el array de datos
    self::$datos = [];

    try {
      // Verificamos si los datos provienen de una solicitud POST
      if ($Solicitud->isPost()) {
        self::$datos = $Solicitud->getParsedBody(); // Datos de la solicitud POST
      }
      // Verificamos si los datos provienen de una solicitud GET
      elseif ($Solicitud->isGet()) {
        self::$datos = $Solicitud->getQueryParams(); // Datos de la solicitud GET
      } else {
        // Si no es GET ni POST, lanzamos un error
        throw new Exception("Método HTTP no soportado: " . $Solicitud->getMethod());
      }
    } catch (Exception $e) {
      // Si hay un error, se puede manejar aquí (por ejemplo, logear el error)
      error_log("Error al procesar los datos de la solicitud: " . $e->getMessage());

      // Si se desea, se puede devolver un mensaje o valor por defecto
      self::$datos = ['error' => 'Hubo un problema al procesar los datos de la solicitud.'];
    }
  }

  public static function obtenerSolicitud(): HTTPSolicitud {
    return self::$Solicitud;
  }

  public static function respuesta($Respuesta) {
    self::$Respuesta = $Respuesta;
  }

  public static function obtenerRespuesta() {
    return self::$Respuesta ?? null;
  }

// Por ejemplo, si necesitas acceder a algún valor de la solicitud
  public static function parametro($campo) {
    return self::$datos[$campo] ?? null;  // Devuelve null si el campo no existe
  }

}