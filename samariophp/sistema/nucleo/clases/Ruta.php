<?php

class Ruta {

    private $uri;
    private $tipo;
    private $metodo;
    public static $Permiso;

    public function __construct($uri, $metodo = null) {
        $this->uri = rtrim($uri, '/'); // Normalizar la URI (eliminar barras al final)
        // Normalizar la ruta vacía
        if ($this->uri === "/") {
            $this->uri = "";
        }
        $this->tipo = $this->detectarTipoRuta();
        $this->metodo = $metodo;
    }

    public function obtenerUri() {
        return $this->uri;
    }

    public function obtenerTipo() {
        return $this->tipo;
    }

    public function obtenerMetodo() {
        return $this->metodo;
    }

    public function esValida() {
        if (is_null($this->tipo)) {
            return false;
        }
        return true;
    }

    /**
     * Detecta el tipo de ruta.
     */
    private function detectarTipoRuta() {

        // Comprobamos si la ruta está en las rutas fijas a través de la clase Rutas
        if (Rutas::esRutaFija($this->uri, $this->metodo)) {
            return Rutas::TIPO_FIJA;
        }

        // Primero, comprobamos si la ruta es pública
        if ($this->esRutaPublica()) {
            return Rutas::TIPO_PUBLICA;
        }
        // Primero, comprobamos si la ruta es pública
        if ($this->esRutaWeb()) {
            return Rutas::TIPO_WEB;
        }

        // Si no está en las rutas fijas, la consideramos dinámica
        if ($this->esDinamica()) {
            return Rutas::TIPO_DINAMICA;
        }

        // Luego, comprobamos si la ruta es privada
        if ($this->esRutaPrivada()) {
            return Rutas::TIPO_PRIVADA;
        }

        return null;
    }

    private function esRutaFija($ruta, $metodo) {
        return Rutas::esRutaFija($ruta, $metodo);
    }

    public static function esFija($ruta, $metodo) {
        return Rutas::esRutaFija($ruta, $metodo);
    }

    /**
     * Verifica si la ruta es pública.
     */
    private function esRutaPublica() {
        return Vistas::esVistaPublica($this->uri);
    }

    // Estos métodos pueden quedarse aquí si son específicos de esta ruta
    public static function esPublica($ruta) {
        return Vistas::esVistaPublica($ruta);
    }

    private function esRutaWeb() {
        return self::esWEB($this->uri);
    }

    public static function esWEB($ruta) {
        return Vistas::esVistaWEB($ruta);
    }

    /**
     * Verifica si la ruta es privada.
     */
    private function esRutaPrivada() {
        return self::esPrivada($this->uri, $this->metodo);
    }

    public static function esPrivada($ruta, $metodo = null) {
        self::$Permiso = self::buscar($ruta, $metodo);
        if (self::$Permiso) {
            return true;
        }
        return false;
    }

    static function buscar($uri, $metodo) {
        return Permiso::donde(['ruta' => $uri, 'metodo_http' => $metodo]);
    }

}