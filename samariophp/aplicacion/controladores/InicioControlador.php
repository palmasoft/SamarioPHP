<?php
namespace SamarioPHP\Controladores;
use Psr\Http\Message\ResponseInterface as Respuesta;
use Psr\Http\Message\ServerRequestInterface as Peticion;

class InicioControlador extends Controlador {
  // Acción para mostrar la página de inicio
  public function mostrarInicio(Peticion $peticion, Respuesta $respuesta) {

    $this->logAplicacion->info('[INICIO] Verificando tablas en la base de datos...');
    try {
      $tablas = \SamarioPHP\BaseDeDatos\BaseDatos::estaVacia();
      if ($tablas > 0) {
        $mensaje = 'Todo está funcionando correctamente';
        $contenido = $this->plantillas->render(VISTA_INICIO, ['mensaje' => $mensaje]);
        $respuesta->getBody()->write($contenido);
      } else {
        $this->logAplicacion->warning('[INICIO] No se encontraron tablas. Redirigiendo a instalación.');
        return $respuesta->withRedirect(RUTA_INSTALAR);
      }
    } catch (PDOException $e) {
      $this->logAplicacion->error('[INICIO] Error al verificar las tablas: ' . $e->getMessage());
      throw $e;
    }

    return $respuesta;
  }
}