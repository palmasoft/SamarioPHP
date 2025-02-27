<?php
use SamarioPHP\Aplicacion\Controladores\AppControlador;
use SamarioPHP\Aplicacion\Controladores\AutenticacionControlador;
use SamarioPHP\Aplicacion\Controladores\InstalacionControlador;
use SamarioPHP\Aplicacion\Controladores\WebControlador;

class GestorRutas {

    private $rutasFijas = [];
    private $db;

    public function __construct() {
        $this->db = \SamarioPHP\Sistema\BaseDatos::iniciar();
        $this->rutasFijas = [
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
    }

    public function esRutaFija($ruta, $metodo) {
        $clave = $ruta . ($metodo === 'POST' ? '_post' : '');
        return isset($this->rutasFijas[$clave]);
    }

    public function ejecutarRutaFija($ruta, $metodo) {
        $clave = $ruta . ($metodo === 'POST' ? '_post' : '');
        if (!$this->esRutaFija($ruta, $metodo)) {
            return false;
        }
        [$controlador, $metodo] = $this->rutasFijas[$clave];
        $instancia = new $controlador();
        return $instancia->$metodo();
    }

    function obtenerRuta($uri, $metodo) {
        return $this->db->query(
                "SELECT * FROM permisos WHERE ruta = :ruta AND metodo = :metodo LIMIT 1",
                ['ruta' => $uri, 'metodo' => $metodo]
        );
    }

    function obtenerControlador($nombreControlador) {
        $clase = "\\SamarioPHP\\Aplicacion\\Controladores\\{$nombreControlador}";
        if (!class_exists($clase)) {
            throw new Exception("El controlador {$nombreControlador} no existe.");
        }
        return new $clase();
    }

    public function resolverRuta($uri) {
        // Normalizar la URI (eliminar barras al final)
        $uri = rtrim($uri, '/');

        // Primero verifica si la ruta es fija
        if (array_key_exists($uri, $this->rutasFijas)) {
            return $this->ejecutarRuta($this->rutasFijas[$uri]);
        }

        // Si no es fija, busca en la base de datos
        return $this->resolverRutaDinamica($uri);
    }

    private function resolverRutaDinamica($uri) {
        // Consulta a la base de datos para obtener controlador y operación
        $sql = "SELECT controlador, operacion FROM rutas WHERE ruta = :ruta LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ruta', $uri);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return $this->ejecutarRuta($resultado);
        }

        // Si no existe la ruta, devuelve un error 404
        http_response_code(404);
        echo "Error 404: Ruta no encontrada.";
    }

    private function ejecutarRuta($config) {
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