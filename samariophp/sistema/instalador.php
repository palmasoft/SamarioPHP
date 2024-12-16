<?php
// Validar conexión a la base de datosprint_r($configuracion);
try {
  $tablasExistentes = $GLOBALS['db']->estaVacia();
} catch (Exception $e) {
  echo $plantillas->render(VISTA_INSTALACION, [
      'mensaje' => 'Error al conectar con la base de datos: ' . $e->getMessage()
  ]);
  exit;
}
//
// Verificar si la base de datos ya contiene tablas
if (!empty($tablasExistentes)) {
  echo $plantillas->render(VISTA_INSTALACION, [
      'mensaje' => 'La instalación no puede continuar. La base de datos ya contiene tablas. Elimine las tablas existentes si desea realizar una nueva instalación.'
  ]);
  exit;
}
//
// 
//  
//   
//    
//     
//      
//       vamos a generar los archivos de migracion insicales y los modelos basicos y obligatoriaos para el sistema.
//         
//
try {
  // Ejecutar el esquema inicial  
  $EsquemaInicial = require_once RUTA_ESQUEMA_INICIAL;

  // Generar migraciones y modelos
  require_once RUTA_GENERAR_MIGRACIONES_MODELOS;
  $resultado = GeneradorMigracionesModelos::generarTodo($EsquemaInicial);
  print_r($resultado);
  //
} catch (Exception $e) {
  // Registrar error en los logs
  $logger->error('Error durante la instalación: ' . $e->getMessage());

  // Mostrar mensaje de error al usuario
  echo $plantillas->render(VISTA_INSTALACION, [
      'mensaje' => 'Hubo un problema durante la instalación. Revise los logs para más detalles.'
  ]);
}

//
//
//
//
//
//
//
//
//
// Intentar realizar la instalación
//
// empezamos la migradera
//
$phinx = require_once RUTA_CONFIG_PHINX;
// Verificar si Phinx está configurado correctamente
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
try {
  // Crear la aplicación Phinx
  $phinxApp = new PhinxApplication();
  $phinxApp->setAutoExit(false); // No permitir que la aplicación Phinx termine el script
  // Ejecutar las migraciones en el entorno de producción
  $input = new StringInput('migrate -e development'); // Puedes cambiar 'production' a otro entorno si es necesario
  $output = new BufferedOutput();

  // Ejecutar la migración
  $phinxApp->run($input, $output);

  // Imprimir la salida
  echo "Migración ejecutada con éxito. Salida: " . $output->fetch();
} catch (Exception $e) {
  // Manejo de errores
  echo "Error al ejecutar la migración: " . $e->getMessage();
}

//// Renombrar el archivo de instalación para evitar accesos posteriores
//$instaladorRealizado = RUTA_LOGS . '/' . uniqid() . '.instalador';
//rename(__FILE__, $instaladorRealizado);
//  $conguracionPhinx = require_once RUTA_CONFIG_PHINX;
// Mostrar mensaje de éxito  
$mensaje = '¡Instalación completada con éxito! El archivo de instalación ya no está accesible.';
//
//
//
//
//
///
//