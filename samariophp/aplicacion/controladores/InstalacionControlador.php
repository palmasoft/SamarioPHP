<?php
namespace SamarioPHP\Controladores; 
\n

use SamarioPHP\Ayudas\GestorLog;
use Psr\Http\Message\ResponseInterface as Respuesta;
use Psr\Http\Message\ServerRequestInterface as Peticion;

class InstalacionControlador extends Controlador {
  // Acción para mostrar el formulario de instalación
  public function mostrarInstalacion(Peticion $peticion, Respuesta $respuesta) {
    GestorLog::log('aplicacion', 'info', '[INSTALACIÓN] Verificando tablas existentes antes de instalar...', ['campos' => 'valores']);

    try {
      $tablas = \SamarioPHP\BaseDeDatos\BaseDatos::estaVacia();

      if (count($tablas) > 0) {
        $mensaje = 'La base de datos ya contiene tablas. Elimine las tablas existentes si desea realizar una nueva instalación.';
        $mensaje_tipo = 'error';
        $this->logAplicacion->warning('[INSTALACIÓN] Tablas existentes detectadas.');
      } else {
        $mensaje = 'Bienvenido al instalador de SamarioPHP. Presione \"Iniciar instalación\" para continuar.';
        $mensaje_tipo = 'iniciar_instalacion';
      }

      $contenido = $this->plantillas->render(VISTA_INSTALACION, [
          'mensaje' => $mensaje,
          'mensaje_tipo' => $mensaje_tipo
      ]);

      $respuesta->getBody()->write($contenido);
    } catch (Exception $e) {
      $this->logAplicacion->error('[INSTALACIÓN] Error al verificar las tablas: ' . $e->getMessage());
      throw $e;
    }

    return $respuesta;
  }

  public function ejecutarInstalacion(Peticion $peticion, Respuesta $respuesta) {
    global $logger, $configuracion, $plantillas;

    $logger->info('[INSTALACIÓN] Iniciando proceso de instalación...');

    try {
      include_once RUTA_INSTALADOR;
      $logger->info('[INSTALACIÓN] Instalación completada con éxito.');

      $contenido = $plantillas->render(VISTA_INSTALACION, [
          'mensaje' => 'Instalación completada con éxito. Ahora puede empezar a usar SamarioPHP.',
          'mensaje_tipo' => 'exito'
      ]);
      $respuesta->getBody()->write($contenido);
      return $respuesta;
    } catch (Exception $e) {
      $logger->error('[INSTALACIÓN] Error durante la instalación: ' . $e->getMessage());

      $contenido = $plantillas->render(VISTA_INSTALACION, [
          'mensaje' => 'Hubo un problema durante la instalación. Revise los registros para más detalles.',
          'mensaje_tipo' => 'error'
      ]);
      $respuesta->getBody()->write($contenido);
      return $respuesta;
    }
  }
}