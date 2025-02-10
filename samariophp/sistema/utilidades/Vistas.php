<?php
namespace SamarioPHP\Sistema\Utilidades;

use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class Vistas {

    private static $twig;

    public static function inicializar($configuracion) {
        $loader = new FilesystemLoader($configuracion['twig']['rutas_vistas']);
        self::$twig = new Environment($loader, [
            'cache' => $configuracion['twig']['cache'],
            'debug' => true
        ]);

        // Variables globales y funciones personalizadas
        self::agregarVariablesGlobales($configuracion);
        self::agregarFuncionesPersonalizadas();
    }

    private static function agregarVariablesGlobales(array $configuracion) {
        self::$twig->addGlobal('app', $configuracion['aplicacion']);
    }

    private static function agregarFuncionesPersonalizadas() {
        self::$twig->addFunction(new TwigFunction('alerta_error', function ($mensaje) {
                    if (empty($mensaje)) {
                        return "";
                    }
                    return '<div class="alerta alerta-error"><strong>Error:</strong> ' . htmlspecialchars($mensaje) . '</div>';
                }, ['is_safe' => ['html']]));
    }

    public static function renderizar(string $vista, array $datos = []): HTTPRespuesta {
        if (!self::$twig) {
            throw new \Exception('Twig no está inicializado. Asegúrate de llamar a Vistas::inicializar() primero.');
        }

        $respuesta = \GestorHTTP::obtenerRespuesta();
        $archivo_vista = $vista . VISTA_EXTENSION;

        // Buscar primero en el módulo
        $partes_vista = explode('.', $vista);
        if (count($partes_vista) == 2) {
            list($modulo, $nombre_vista) = $partes_vista;
            $archivo_vista = $nombre_vista . VISTA_EXTENSION;
            $ruta_vista = 'componentes/' . $modulo . '/vistas/' . $archivo_vista;
            if (file_exists(DIR_APP . $ruta_vista)) {
                $html = self::$twig->render($ruta_vista, $datos);
                $respuesta->getBody()->write($html);
                return $respuesta;
            } else {
                throw new \Exception("La vista '{$archivo_vista}' no existe en [ " . DIR_APP . $ruta_vista . " ].");
            }
        } else {
            if (file_exists(DIR_APP . $archivo_vista)) {
                $html = self::$twig->render($archivo_vista, $datos);
                $respuesta->getBody()->write($html);
                return $respuesta;
            }
        }

        // Buscar en las vistas públicas
        if (file_exists(DIR_VISTAS_PUBLICAS . $archivo_vista)) {
            $html = self::$twig->render($archivo_vista, $datos);
            $respuesta->getBody()->write($html);
            return $respuesta;
        }

        // Buscar en la carpeta de vistas estándar
        if (file_exists(DIR_PUBLICO . $archivo_vista)) {
            $html = self::$twig->render($archivo_vista, $datos);
            $respuesta->getBody()->write($html);
            return $respuesta;
        }

        // Si no se encuentra la vista
        throw new \Exception("La vista '{$archivo_vista}' no existe en [ " . DIR_PUBLICO . " ].");
    }

}