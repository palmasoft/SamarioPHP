<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('SAMARIOPHP_INICIO', microtime(true));
define('DIR_FRAMEWORK', __DIR__ . DIRECTORY_SEPARATOR);
// Cargar la configuración
require_once DIR_FRAMEWORK . 'samariophp/booteador.php';
