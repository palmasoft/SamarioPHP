<?php

namespace SamarioPHP\Sistema\Middleware;

use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;
use Psr\Http\Server\RequestHandlerInterface;
use SamarioPHP\Sistema\Utilidades\Log;
use SamarioPHP\Sistema\Utilidades\BD;

class VerificarInstalacionMiddleware {

    protected $logger;

    public function __construct() {
        Log::inicializar();
    }

    public function __invoke(HTTPSolicitud $request, RequestHandlerInterface $handler): HTTPRespuesta {
//        $tocaInstalar = false;
//        Log::info('[MIDDLEWARE] Verificando tablas en la base de datos...');
//
//        try {
//            if (BD::vacia() === 0) {
//                Log::warning('[MIDDLEWARE] No se encontraron tablas. Redirigiendo a instalación.');
//                $tocaInstalar = true;
//            }
//        } catch (\PDOException $e) {
//            Log::error('[MIDDLEWARE] Error al verificar las tablas: ' . $e->getMessage());
//            throw $e;
//        }
//
//        // Verificar si el archivo de instalación completa existe
//        $archivoInstalacion = RUTA_LOGS . '/instalacion_completa.php';
//        $rutaActual = $request->getUri()->getPath();
//
//        if (!file_exists($archivoInstalacion)) {
//            $tocaInstalar = true;
//        } else {
//            // Si está instalado y la ruta es la de instalación, redirigir al inicio
//            if (strpos($rutaActual, RUTA_INSTALAR) !== false) {
//                Log::info('[MIDDLEWARE] El sistema ya está instalado. Redirigiendo al inicio.');
//                $response = new \Slim\Psr7\Response();
//                return $response->withHeader('Location', RUTA_INICIO)->withStatus(302);
//            }
//        }
//
//        // Si no está instalado, redirigir a la ruta de instalación
//        if ($tocaInstalar) {
//            $response = new \Slim\Psr7\Response();
//            return $response->withHeader('Location', RUTA_INSTALAR)->withStatus(302);
//        }

        // Si todo está bien, pasar la solicitud al siguiente middleware/controlador
        return $handler->handle($request);
    }
}
