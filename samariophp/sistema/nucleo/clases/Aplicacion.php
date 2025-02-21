<?php
namespace SamarioPHP\Sistema;

use Slim\Factory\AppFactory;
use SamarioPHP\Sistema\Auth;
use SamarioPHP\Sistema\Servicios\SesionServicio;
use SamarioPHP\Sistema\Servicios\UsuarioServicio;
use SamarioPHP\Sistema\Servicios\AutenticacionServicio;
use SamarioPHP\Sistema\Middleware\GestorHTTPMiddleware;
use SamarioPHP\Sistema\VerificarInstalacionMiddleware;
use SamarioPHP\Sistema\Utilidades\Log;
use SamarioPHP\Sistema\Utilidades\Vistas;
use SamarioPHP\Sistema\BaseDatos;

class Aplicacion {

    private static $instancia;
    private $app;
    private $configuracion;

    private function __construct() {
        $this->inicializarComponentes();
        $this->configurarSlim();
    }

    public static function obtenerInstancia() {
        if (!self::$instancia) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    private function inicializarComponentes() {

        require_once RUTA_FUNCIONES;
        // Inicializar log
        Log::inicializar();

        // Cargar configuración global
        $this->configuracion = require_once RUTA_CONFIGURACION;
        $this->validarConfiguracion($this->configuracion);

        // Manejo de errores
        $gestorErrores = require_once RUTA_CONFIG_ERRORES;
        $this->inicializarManejoErrores($gestorErrores);

        // Cargar configuración para Medoo desde una ruta definida
        $configMedoo = require_once RUTA_CONFIG_MEEDO;
        BaseDatos::iniciar($configMedoo($this->configuracion));
        
        // Inicializar la sesión
        Auth::setServicio(new AutenticacionServicio(new UsuarioServicio()));
    }

    private function inicializarManejoErrores($configuracionErrores) {
        $manejadorErrores = new \SamarioPHP\Sistema\Utilidades\Errores($configuracionErrores);
        $manejadorErrores->inicializar();
    }

    private function configurarSlim() {
        $configuracionSlim = require_once RUTA_CONFIG_SLIM;
        $this->app = \Slim\Factory\AppFactory::create();
        $this->app->addRoutingMiddleware();

//        // Configurar manejo de errores usando los parámetros del archivo de configuración
//        $errorMiddleware = $this->app->addErrorMiddleware(
//            $configuracionSlim['error_middleware']['mostrar_errores'],
//            $configuracionSlim['error_middleware']['log_errores'],
//            $configuracionSlim['error_middleware']['mostrar_detalles']
//        );

//        // Configurar manejo de errores 404
//        $errorMiddleware->setErrorHandler(\Slim\Exception\HttpNotFoundException::class, function () {
//            return $this->renderizarVista404();
//        });
//
//        // Configurar manejo de errores 405 (método no permitido)
//        $errorMiddleware->setErrorHandler(\Slim\Exception\HttpMethodNotAllowedException::class, function () {
//            return $this->renderizarVista405();
//        });
//
//        // Configurar manejo de errores 500 (errores internos del servidor)
//        $errorMiddleware->setDefaultErrorHandler(function () {
//            return $this->renderizarVistaError();
//        });
        // Middlewares personalizados
        $this->app->add(new GestorHTTPMiddleware());
        //$this->app->add(new VerificarInstalacionMiddleware()); 
        // Cargar rutas
        $rutas = require_once RUTA_ENRUTADOR;
        $rutas($this->app);
    }

    private function validarConfiguracion($configuracion) {
        $validador = require_once RUTA_CONFIG_VALIDACION;
        try {
            $validador($configuracion);
        } catch (\Exception $e) {
            Log::critico('Error en la configuración: ' . $e->getMessage());
            exit('La configuración del sistema es inválida. Por favor, revisa el archivo de configuración.');
        }
    }

    public function arrancar() {
// Registro de inicio del sistema
        Log::info('El sistema inició correctamente.');

// Ejecutar la aplicación Slim
        $this->app->run();
    }

    private function renderizarVista404() {
        $respuesta = $this->app->getResponseFactory()->createResponse();

        $twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(RUTA_VISTAS));
        $contenido = $twig->render('404.html.php', [
            'codigo_error' => 404,
            'mensaje_error' => 'La página que buscas no existe.',
        ]);

        $respuesta->getBody()->write($contenido);
        return $respuesta->withStatus(404);
    }

    private function renderizarVista405() {
        $respuesta = $this->app->getResponseFactory()->createResponse();

        $twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(RUTA_VISTAS));
        $contenido = $twig->render('405.html.php', [
            'codigo_error' => 405,
            'mensaje_error' => 'Método no permitido.',
        ]);

        $respuesta->getBody()->write($contenido);
        return $respuesta->withStatus(405);
    }

    private function renderizarVistaError() {
        return vista('errores/errores', [
            'codigo_error' => 500,
            'mensaje_error' => 'Ocurrió un error en el servidor. Intenta nuevamente más tarde.',
        ]);
    }

}