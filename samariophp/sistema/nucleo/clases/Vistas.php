<?php
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class Vistas {

    private static $twig;

    public static function inicializar() {
        $configuracion = require_once RUTA_CONFIG_TWIG;
        self::configurarTwig($configuracion);
        self::agregarVariablesGlobales();
        self::agregarFuncionesPersonalizadas();
    }

    private static function configurarTwig($configuracion) {
        $loader = new FilesystemLoader($configuracion['rutas_vistas']);
        self::$twig = new Environment($loader, [
            'cache' => $configuracion['cache'],
            'debug' => true
        ]);
    }

    private static function agregarVariablesGlobales() {
        self::$twig->addGlobal('app', config('aplicacion'));
    }

    private static function agregarFuncionesPersonalizadas() {
        // Función personalizada para mostrar un mensaje de alerta de error
        self::$twig->addFunction(new TwigFunction('alerta_error', function ($mensaje) {
                    return empty($mensaje) ? "" : '<div class="alerta alerta-error"><strong>Error:</strong> ' . htmlspecialchars($mensaje) . '</div>';
                }, ['is_safe' => ['html']]));

        // Función personalizada para formatear fechas
        self::$twig->addFunction(new TwigFunction('formato_fecha', function ($fecha) {
                    // Verificamos si la fecha está vacía o no es válida
                    if (empty($fecha)) {
                        return '';
                    }
                    // Intentamos formatear la fecha usando strtotime
                    $timestamp = strtotime($fecha);
                    if ($timestamp === false) {
                        return ''; // Si no es una fecha válida, devolvemos una cadena vacía
                    }
                    return date('Y/m/d', $timestamp); // Formato de fecha: día/mes/año
                }));
    }

    public static function esVistaDisponible(string $ruta, string $directorio): bool {
        return file_exists($directorio . $ruta . VISTA_EXTENSION);
    }

    public static function renderizar(string $vista, array $datos = []): HTTPRespuesta {
        if (!self::$twig) {
            self::inicializar();
        }

        $respuesta = \GestorHTTP::obtenerRespuesta();
        $archivo_vista = $vista . VISTA_EXTENSION;

        if (self::renderizarDesdeModulo($vista, $datos, $respuesta)) {
            return $respuesta;
        }

        if (self::renderizarDesdeDirectorio(DIR_PAGINASWEB, $archivo_vista, $datos, $respuesta)) {
            return $respuesta;
        }

        if (self::renderizarDesdeDirectorio(DIR_VISTAS_PUBLICAS, $archivo_vista, $datos, $respuesta)) {
            return $respuesta;
        }

        throw new \Exception("La vista '{$archivo_vista}' no existe en [ " . DIR_PUBLICO . " ].");
    }

    private static function renderizarDesdeModulo(string $vista, array $datos, HTTPRespuesta &$respuesta): bool {
        $partes_vista = explode('.', $vista);
        if (count($partes_vista) !== 2) {
            return false;
        }
        list($modulo, $nombre_vista) = $partes_vista;
        $ruta_vista = "componentes/{$modulo}/vistas/" . $nombre_vista . VISTA_EXTENSION;
        return self::renderizarDesdeDirectorio(DIR_APP, $ruta_vista, $datos, $respuesta);
    }

    private static function renderizarDesdeDirectorio(string $directorio, string $archivo_vista, array $datos, HTTPRespuesta &$respuesta): bool {
        if (file_exists($directorio . $archivo_vista)) {
            $html = self::$twig->render($archivo_vista, $datos);
            $respuesta->getBody()->write($html);
            return true;
        }
        return false;
    }

    /**
     * Verificar si una vista está en la carpeta de vistas privadas.
     */
    public static function esVistaPublica($uri) {
        $rutaVistaPrivada = DIR_VISTAS_PUBLICAS . $uri . '.php';
        return file_exists($rutaVistaPrivada);
    }

    /**
     * Verificar si una vista está en la carpeta de vistas WEB.
     */
    public static function esVistaWEB($uri) {
        $rutaVistaWEB = DIR_PAGINASWEB . $uri . '.php';
        return file_exists($rutaVistaWEB);
    }

}