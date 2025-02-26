<?php

use SamarioPHP\Aplicacion\Controladores\AppControlador;
use SamarioPHP\Aplicacion\Controladores\AutenticacionControlador;
use SamarioPHP\Aplicacion\Controladores\InstalacionControlador;
use SamarioPHP\Aplicacion\Controladores\WebControlador;

class Ruta {

    private $rutasFijas = [
        "" => [WebControlador::class, 'mostrarInicio'],
        "inicio" => [WebControlador::class, 'mostrarInicio'],
        "instalar" => [InstalacionControlador::class, 'mostrarInstalacion'],
        "instalar_post" => [InstalacionControlador::class, 'ejecutarInstalacion'],
        "registro" => [AutenticacionControlador::class, 'mostrarVistaRegistro'],
        "registro_post" => [AutenticacionControlador::class, 'procesarRegistro'],
        "verificar" => [AutenticacionControlador::class, 'verificarCorreoElectronico'],
        "recuperar-clave" => [AutenticacionControlador::class, 'mostrarFormularioRecuperarClave'],
        "inicio-sesion" => [AutenticacionControlador::class, 'mostrarFormularioLogin'],
        "inicio-sesion_post" => [AutenticacionControlador::class, 'procesarLogin'],
        "usuario/salir" => [AutenticacionControlador::class, 'cerrarSesion'],
        "usuario/salir_post" => [AutenticacionControlador::class, 'cerrarSesion'],
        "admin" => [AppControlador::class, 'mostrarPanelAdministracion'],
    ];

    public static function esPublica(string $ruta) {
        return Vistas::esVistaPublica($ruta);
    }

    public static function esPrivada(string $ruta, $metodo) {
        if (self::buscar($ruta, $metodo)) {
            return true;
        }
        return false;
    }

    public static function esRutaFija($ruta, $metodo) {
        $clave = $ruta . ($metodo === 'POST' ? '_post' : '');
        return isset(self::$rutasFijas[$clave]);
    }

    public static function ejecutarRutaFija($ruta, $metodo) {
        $clave = $ruta . ($metodo === 'POST' ? '_post' : '');
        if (!self::esRutaFija($ruta, $metodo)) {
            return false;
        }
        [$controlador, $metodo] = self::$rutasFijas[$clave];
        $instancia = new $controlador();
        return $instancia->$metodo();
    }

    static function buscar($uri, $metodo) {
        return Permiso::donde(['ruta' => $uri, 'metodo_http' => $metodo]);
    }

    static function obtenerControlador($nombreControlador) {
        $clase = "\\SamarioPHP\\Aplicacion\\Controladores\\{$nombreControlador}";
        if (!class_exists($clase)) {
            throw new Exception("El controlador {$nombreControlador} no existe.");
        }
        return new $clase();
    }

    public static function resolverRuta($uri) {
        // Normalizar la URI (eliminar barras al final)
        $uri = rtrim($uri, '/');

        // Primero verifica si la ruta es fija
        if (array_key_exists($uri, self::$rutasFijas)) {
            return self::ejecutarRuta(self::$rutasFijas[$uri]);
        }

        // Si no es fija, busca en la base de datos
        return self::resolverRutaDinamica($uri);
    }

    private static function resolverRutaDinamica($uri) {
        // Consulta a la base de datos para obtener controlador y operación
        $sql = "SELECT controlador, operacion FROM rutas WHERE ruta = :ruta LIMIT 1";
        $stmt = self::db->prepare($sql);
        $stmt->bindParam(':ruta', $uri);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return self::ejecutarRuta($resultado);
        }

        // Si no existe la ruta, devuelve un error 404
        http_response_code(404);
        echo "Error 404: Ruta no encontrada.";
    }

    private static function ejecutarRuta($config) {
        $controladorNombre = "\\Controladores\\" . $config['controlador'];
        $operacion = $config['operacion'];

        if (class_exists($controladorNombre)) {
            $controlador = new $controladorNombre();

            if (method_exists($controlador, $operacion)) {
                return $controlador->$operacion();
            }
        }

        // Error si no existe controlador u operación
        http_response_code(500);
        echo "Error 500: No se pudo ejecutar la operación.";
    }
}
