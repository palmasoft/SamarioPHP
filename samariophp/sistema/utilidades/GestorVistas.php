<?php
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;

class GestorVistas {

  protected $plantillas;
  protected $respuesta;

  public function __construct($plantillas, $respuesta) {
    $this->plantillas = $plantillas;
    $this->respuesta = $respuesta;
  }

  public function renderizar(string $vista, array $datos = []): HTTPRespuesta {
    $this->respuesta = \GestorHTTP::obtenerRespuesta();
    $archivo_vista = $vista . VISTA_EXTENSION;

    // Buscar primero en el módulo
    $partes_vista = explode('.', $vista);
    if (count($partes_vista) == 2) {
      // Caso: 'autenticacion.login'
      list($modulo, $nombre_vista) = $partes_vista;
      $archivo_vista = $nombre_vista . VISTA_EXTENSION;
      $ruta_vista = $modulo . '/vistas/' . $archivo_vista;
      if (file_exists(DIR_APP . $ruta_vista)) {
        $html = $this->plantillas->render($ruta_vista, $datos);
        $this->respuesta->getBody()->write($html);
        return $this->respuesta;
      } else {
        throw new \Exception("La vista '{$archivo_vista}' no existe en la ubicacion [ " . (DIR_APP . $ruta_vista ) . " ].");
      }
    }

    // Buscar en las vistas públicas
    if (file_exists(DIR_VISTAS_PUBLICAS . $archivo_vista)) {
      $html = $this->plantillas->render($archivo_vista, $datos);
      $this->respuesta->getBody()->write($html);
      return $this->respuesta;
    }

    // Buscar en la carpeta de vistas estándar
    if (file_exists(DIR_PUBLICO . $archivo_vista)) {
      $html = $this->plantillas->render($archivo_vista, $datos);
      $this->respuesta->getBody()->write($html);
      return $this->respuesta;
    }

    // Si no se encuentra la vista
    throw new \Exception("La vista '{$archivo_vista}' no existe en la ubicacion [ " . DIR_PUBLICO . " ].");
  }

}