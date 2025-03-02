<?php
use SamarioPHP\Aplicacion\Controladores\AppControlador;
use SamarioPHP\Aplicacion\Controladores\InstalacionControlador;
use SamarioPHP\Aplicacion\Controladores\WebControlador;

class Rutas {

    const TIPO_FIJA = 'fija';
    const TIPO_DINAMICA = 'dinamica';
    const TIPO_PUBLICA = 'publica';
    const TIPO_PRIVADA = 'privada';
    const TIPO_WEB = 'web';

    private static $rutasFijas = [
        "" => [WebControlador::class, 'mostrarInicio'],
        "inicio" => [WebControlador::class, 'mostrarInicio'],
        "instalar" => [InstalacionControlador::class, 'mostrarInstalacion'],
        "instalar_post" => [InstalacionControlador::class, 'ejecutarInstalacion'],
        "admin" => [AppControlador::class, 'mostrarPanelAdministracion'],
    ];

    /**
     * Cargar rutas fijas desde archivos de componentes.
     */
    public static function cargarRutasDesdeComponentes() {
// Ruta base donde se encuentran los archivos de rutas dentro de cada componente
        $rutaComponentes = DIR_COMPONENTES;

// Buscar los directorios en la ruta de componentes
        $directorios = glob($rutaComponentes . '/*', GLOB_ONLYDIR);

        foreach ($directorios as $directorio) {
// Intentar cargar el archivo rutas.php de cada componente
            $archivoRutas = $directorio . '/rutas.php';
            if (file_exists($archivoRutas)) {
// Incluir el archivo de rutas y fusionar las rutas encontradas
                $rutasComponente = require $archivoRutas;
                self::$rutasFijas = array_merge(self::$rutasFijas, $rutasComponente);
            }
        }
    }

    public static function rutaNoValida() {
        http_response_code(404);
        echo "<h1>Error 404</h1><p>La página que buscas no existe.</p>";
// Alternativamente, puedes redirigir a una página específica
// header("Location: /error-404");
        exit;
    }

    public static function resolverRuta(Ruta $Ruta) {
// Normalizar la URI (eliminar barras al final)
        $ruta = rtrim($Ruta->obtenerUri(), '/');
        $metodo = $Ruta->obtenerMetodo();
        // Normalizar la ruta vacía
        if ($ruta === "/" or $ruta === "") {
            $ruta = "inicio";
        }
        if (Ruta::esFija($ruta, $metodo)) {
            return Rutas::ejecutarRutaFija($ruta, $metodo);
        }
        if (Ruta::esWeb($ruta)) {
            return Rutas::resolverRuta($ruta, $metodo);
        }
        if (Ruta::esPrivada($ruta) and Sesion::estaLogueado()) {
            return Rutas::ejecutarRuta($ruta, $metodo);
        }

        // Si no es fija, busca en la base de datos
        return self::resolverRutaDinamica($ruta);
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
        
        [$controlador, $funcion] = self::$rutasFijas[$clave];
        $instancia = new $controlador();
        return $instancia->$funcion();
    }

    static function obtenerControlador($nombreControlador) {
        $clase = "\\SamarioPHP\\Aplicacion\\Controladores\\{$nombreControlador}";
        if (!class_exists($clase)) {
            throw new Exception("El controlador {$nombreControlador} no existe.");
        }
        return new $clase();
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

    private function obtenerRuta($uri, $metodo) {
        return $this->db->query(
                "SELECT * FROM permisos WHERE ruta = :ruta AND metodo = :metodo LIMIT 1",
                ['ruta' => $uri, 'metodo' => $metodo]
        );
    }

}