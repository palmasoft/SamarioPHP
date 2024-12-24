<?php
namespace SamarioPHP\Aplicacion\Controladores;

use Psr\Http\Message\ResponseInterface as Respuesta;
use Psr\Http\Message\ServerRequestInterface as Peticion;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class InstalacionControlador extends Controlador {

  // Mostrar formulario de instalación
  public function mostrarInstalacion(Peticion $peticion, Respuesta $respuesta) {
    try {
      $tablas = \SamarioPHP\BaseDeDatos\BaseDatos::estaVacia();
      if (!empty($tablas)) {
        $mensaje = 'La base de datos ya contiene tablas. Elimine las tablas existentes para una nueva instalación.';
        $mensaje_tipo = 'error';
      } else {
        $mensaje = 'Bienvenido al instalador. Presione "Iniciar instalación" para continuar.';
        $mensaje_tipo = 'iniciar_instalacion';
      }

      $contenido = $this->plantillas->render(VISTA_INSTALACION, compact('mensaje', 'mensaje_tipo'));
      $respuesta->getBody()->write($contenido);
    } catch (\Exception $e) {
      $this->logError('Error al verificar las tablas: ' . $e->getMessage());
      throw $e;
    }

    return $respuesta;
  }

  // Ejecutar instalación
  public function ejecutarInstalacion(Peticion $peticion, Respuesta $respuesta) {
    try {
      // Validar conexión a la base de datos
      $this->validarConexion();

      // Generar migraciones y ejecutar esquema inicial
      $this->generarMigraciones();
      $salidaMigraciones = $this->ejecutarMigraciones();
      $salidaSeeders = $this->ejecutarSeeders();

      // Marcar la instalación como completada y proteger el instalador
      $this->marcarInstalacionCompletada();
      $this->protegerInstalador();

      $mensaje = '¡Instalación completada con éxito! Ahora puede usar SamarioPHP.';
      $mensaje_tipo = 'exito';
    } catch (\Exception $e) {
      $mensaje = 'Hubo un problema durante la instalación. Revise los registros para más detalles.';
      $mensaje_tipo = 'error';
      $this->logError('Error durante la instalación: ' . $e->getMessage());
    }

    $contenido = $this->plantillas->render(VISTA_INSTALACION_TERMINADA, compact('mensaje', 'mensaje_tipo', 'salidaMigraciones', 'salidaSeeders'));
    $respuesta->getBody()->write($contenido);
    return $respuesta;
  }

  private function validarConexion() {
    $tablasExistentes = \SamarioPHP\BaseDeDatos\BaseDatos::estaVacia();
    if (!empty($tablasExistentes)) {
      throw new \Exception('La base de datos ya contiene tablas.');
    }
  }

  private function generarMigraciones() {
    // Generar migraciones y modelos
    require_once RUTA_GENERAR_MIGRACIONES_MODELOS;
    $EsquemaInicial = require RUTA_ESQUEMA_INICIAL;
    \GeneradorMigracionesModelos::generarTodo($EsquemaInicial);
  }

  private function ejecutarMigraciones() {
    $phinxApp = new PhinxApplication();
    $phinxApp->setAutoExit(false);
    $input = new StringInput('migrate -e development');
    $output = new BufferedOutput();
    $phinxApp->run($input, $output);

// Ahora obtenemos la salida y la mostramos en la terminal
    $respuestaTerminal = $output->fetch();
    return $respuestaTerminal;
  }

  private function ejecutarSeeders() {
    $phinxApp = new PhinxApplication();
    $phinxApp->setAutoExit(false);
    $input = new StringInput('seed:run -e development');
    $output = new BufferedOutput();
    $phinxApp->run($input, $output);

// Ahora obtenemos la salida y la mostramos en la terminal
    $respuestaTerminal = $output->fetch();
    return $respuestaTerminal;
  }

  private function marcarInstalacionCompletada() {
//    // Crear el archivo que indica que la instalación se completó
//    $archivoInstalacion = RUTA_LOGS . '/instalacion_completa.php'; // Ajusta la ruta si es necesario
//    if (!file_put_contents($archivoInstalacion, '<?php // Instalación completada')) {
//      throw new \Exception('No se pudo crear el archivo de instalación completa.');
//    }
  }

  private function protegerInstalador() {
//    $nuevoNombre = RUTA_LOGS . '/' . uniqid() . '.instalador';
//    if (!rename(__FILE__, $nuevoNombre)) {
//      throw new \Exception('No se pudo renombrar el archivo de instalación.');
//    }
//    // Cambiar los permisos del archivo de instalación para protegerlo    
//    if (!chmod($nuevoNombre, 0000)) {  // Establece permisos 0000 (sin permisos)
//      throw new \Exception('No se pudieron cambiar los permisos del instalador.');
//    }
  }

  private function logError($mensaje) {
    \GestorLog::log('aplicacion', 'error', '[INSTALACIÓN] ' . $mensaje);
  }

}