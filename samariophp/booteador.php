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
// Configuración de Slim
$slimConfig = require_once RUTA_CONFIG_SLIM;
$GLOBALS['aplicacion'] = $aplicacion = $slimConfig($configuracion, $plantillas, $loggers['aplicacion']);
///
///
$aplicacion->add(new \SamarioPHP\Middleware\MiddlewareGestorHTTP());
$aplicacion->add(new \SamarioPHP\Middleware\VerificarInstalacionMiddleware($baseDeDatos, $loggers['aplicacion']));
// 
// Cargar rutas
$rutas = require_once RUTA_ENRUTADOR;
$rutas($aplicacion, $configuracion, $baseDeDatos, $plantillas, $loggers);
//
// Registro de inicio del sistema
$loggers['aplicacion']->info('El sistema inició correctamente.');
