<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('SAMARIOPHP_INICIO', microtime(true));
require_once __DIR__ . '/samariophp/booteador.php';
// Iniciar la aplicación 
$aplicacion->run();
