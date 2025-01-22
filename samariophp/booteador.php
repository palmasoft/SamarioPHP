<?php
// Cargar la configuración
require_once __DIR__ . '/../base.php';
//cargar librerias de composer
require_once RUTA_AUTOLOAD; //
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
// Middleware para verificar permisos
//
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
