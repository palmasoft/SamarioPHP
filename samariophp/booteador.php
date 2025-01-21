<?php
// Cargar la configuración
require_once __DIR__ . '/../base.php';
//cargar librerias de composer
require_once RUTA_LIBRERIAS; //
//
//datos de configuracion global
$configuracion = require_once RUTA_CONFIGURACION;
// Validar configuración crítica
$validador = require_once RUTA_CONFIG_VALIDACION;
$validador($configuracion);
$GLOBALS['config'] = $configuracion;
//
// // 
// Configurar logs con Monolog
// Cargar configuración de logs
$configurarLogs = require_once RUTA_CONFIG_LOGS;
$GLOBALS['loggers'] = $loggers = $configurarLogs();
// 
//
// Manejo de errores
$gestorErrores = require_once RUTA_CONFIG_ERRORES;
$GLOBALS['errores'] = $gestorErrores($configuracion, $loggers['servidor']);
// 
// 
// Configuración de Medoo para la base de datos
// Inicialización del gestor de base de datos
$gestorDatos = require_once RUTA_CONFIG_BASEDEDATOS;
$GLOBALS['datos'] = $baseDeDatos = $gestorDatos($configuracion, $loggers['aplicacion']); // Crear la conexión y hacerla global para todo el proyecto
//
//
// Configuración de Twig
$twigConfig = require_once RUTA_CONFIG_TWIG;
$GLOBALS['plantillas'] = $plantillas = $twigConfig($configuracion, $loggers['aplicacion']);
///
// Configuración de Slim
$slimConfig = require_once RUTA_CONFIG_SLIM;
$GLOBALS['aplicacion'] = $aplicacion = $slimConfig($configuracion, $plantillas, $loggers['aplicacion']);
///
//
//
// Crear los servicios necesarios
// Servicio de sesion
use SamarioPHP\Aplicacion\Servicios\Sesion;

$GLOBALS['sesion'] = $sesionServicio = new Sesion();
// Servicio de autenticación
use SamarioPHP\Aplicacion\Servicios\Autenticacion;

$GLOBALS['autenticacion'] = $autenticacionServicio = new Autenticacion();
// Servicio de correos
use SamarioPHP\Aplicacion\Servicios\CorreoElectronico;

$GLOBALS['enviador_correos'] = $correoElectronicoServicio = new CorreoElectronico($configuracion);  // Servicio de envío de correos electrónicos
//
//
function obtenerRuta($uri, $metodo) {
  $db = new Conexion(); // Clase para la conexión
  return $db->query(
          "SELECT * FROM permisos WHERE ruta = :ruta AND metodo = :metodo LIMIT 1",
          ['ruta' => $uri, 'metodo' => $metodo]
  );
}

function obtenerControlador($nombreControlador) {
  $clase = "\\App\\Controladores\\{$nombreControlador}";
  if (!class_exists($clase)) {
    throw new Exception("El controlador {$nombreControlador} no existe.");
  }
  return new $clase();
}

function usuarioTienePermiso($permiso) {
  // Verificar si el usuario actual tiene el permiso requerido
  // Este método debe conectarse al sistema de roles/usuarios
  return true; // Solo como ejemplo
}

// Middleware para verificar permisos
//
$aplicacion->add(function ($request, $handler) {
  $uri = $request->getUri()->getPath();
  $metodo = $request->getMethod();

  // Consultar la ruta en la base de datos
  $ruta = obtenerRuta($uri, $metodo);

  if (!$ruta) {
    return $handler->handle($request)
            ->withStatus(404)
            ->write('Ruta no encontrada');
  }

  // Validar permisos del usuario
  if (!usuarioTienePermiso($ruta['permiso'])) {
    return $handler->handle($request)
            ->withStatus(403)
            ->write('Acceso denegado');
  }

  // Llamar al controlador asociado
  $controlador = obtenerControlador($ruta['controlador']);
  $accion = $ruta['accion'];

  if (!method_exists($controlador, $accion)) {
    throw new Exception("La acción {$accion} no existe en el controlador {$ruta['controlador']}.");
  }

  // Ejecutar la acción del controlador
  return $controlador->{$accion}($request, $handler);
});
//
$aplicacion->add(new \SamarioPHP\Middleware\GestorHTTPMiddleware());
$aplicacion->add(new \SamarioPHP\Middleware\VerificarInstalacionMiddleware($baseDeDatos, $loggers['aplicacion']));
// 
// Cargar rutas
$rutas = require_once RUTA_ENRUTADOR;
$rutas($aplicacion, $configuracion, $baseDeDatos, $plantillas, $loggers);
//
// Registro de inicio del sistema
$loggers['aplicacion']->info('El sistema inició correctamente.');
