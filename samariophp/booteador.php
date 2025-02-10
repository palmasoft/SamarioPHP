<?php
//cargar librerias de composer
require_once RUTA_AUTOLOAD; //
use SamarioPHP\Sistema\Aplicacion;

(Aplicacion::obtenerInstancia())
    ->arrancar();
