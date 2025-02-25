<?php
namespace SamarioPHP\Sistema\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use SamarioPHP\Sistema\Auth;

class AutenticacionMiddleware {

    private $gestorRutas;

    public function __construct(\GestorRutas $gestorRutas) {
        $this->gestorRutas = $gestorRutas;
    }

    public function __invoke(Request $request, Handler $handler): Response {
        session_start();

        // Obtener la ruta desde la URL
        $ruta = trim(parse_url($request->getUri()->getPath(), PHP_URL_PATH), '/');
        $metodo = $request->getMethod();

        // Si la vista existe en /publico/vistas/, la consideramos pública
        if ($ruta === '' || esVistaPublica($ruta)) {
            return $handler->handle($request);
        }


        // Si la ruta es fija y gestionada por el GestorRutas, dejarla pasar
        if ($this->gestorRutas->esRutaFija($ruta, $metodo)) {
            return $handler->handle($request);
        }

        // Verificar si el usuario está autenticado usando Auth
        if (!Auth::obtenerUsuarioAutenticado()) {
            $response = new \Slim\Psr7\Response();

            if ($request->getHeaderLine('Accept') === 'application/json') {
                $respuesta = Respuesta::error('No autenticado');
                $response->getBody()->write($respuesta->comoJson());
                return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
            }

            // Escribir algo en el cuerpo de la respuesta antes de redirigir
            $response->getBody()->write('Redireccionando...');
            return $response->withHeader('Location', RUTA_USUARIO_ENTRAR)->withStatus(302);
        }

        // Si el usuario está autenticado, continuar con la ejecución de la ruta
        return $handler->handle($request);
    }

}