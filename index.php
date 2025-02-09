<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('SAMARIOPHP_INICIO', microtime(true));
// Cargar la configuración
require_once __DIR__ . '/base.php';
require_once __DIR__ . '/samariophp/booteador.php';
