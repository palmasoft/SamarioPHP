<?php

class Respuesta {

    public $tipo;
    public  $mensaje;
    public  $datos;

    public function __construct($tipo = 'info', $mensaje = '', $datos = []) {
        $this->tipo = $tipo;
        $this->mensaje = $mensaje;
        $this->datos = $datos;
    }

    public static function exito($mensaje, $datos = []) {
        return new self('exito', $mensaje, $datos);
    }

    public static function error($mensaje, $datos = []) {
        return new self('error', $mensaje, $datos);
    }

    public static function alerta($mensaje, $datos = []) {
        return new self('alerta', $mensaje, $datos);
    }

    public function comoJson() {
        return json_encode($this->comoArray());
    }

    public function comoArray() {
        return [
            'tipo' => $this->tipo,
            'mensaje' => $this->mensaje,
            'datos' => $this->datos
        ];
    }

}