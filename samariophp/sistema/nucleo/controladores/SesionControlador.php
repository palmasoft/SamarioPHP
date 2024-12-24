<?php
namespace SamarioPHP\Aplicacion\Controladores;

use SamarioPHP\BaseDeDatos\BaseDatos;
use PHPAuth\Auth;
use PHPAuth\Config as AuthConfig;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; // Si usas excepciones personalizadas de PHPMailer

class SesionControlador {

  protected $auth;

  public function __construct() {
    // Obtener la conexión Medoo
    $baseDeDatos = BaseDatos::iniciar($GLOBALS['config']['base_de_datos']); // Esto debe devolver una instancia de Medoo
    // Obtener el objeto PDO desde Medoo
    $pdo = $baseDeDatos->pdo;
    // Crear instancia de configuración de PHPAuth
    $config = new AuthConfig($pdo);
    // Configuración personalizada de tablas
//    $config->set('table_users', 'usuarios');
//    $config->set('table_sessions', 'sesiones');
//    $config->set('phpauth_config', 'configuracion_phpauth');
    // Crear instancia de Auth con la configuración
    $this->auth = new Auth($pdo, $config);
  }

  public function iniciarSesion($correo, $contrasena) {
    return $this->auth->login($correo, $contrasena);
  }

  public function cerrarSesion($hash) {
    return $this->auth->logout($hash);
  }

  public function registrarUsuario($correo, $contrasena, $rcontrasena, $params = []) {
    return $this->auth->register($correo, $contrasena, $rcontrasena, $params ?? []);
  }

  public function verificarUsuario($token) {
    return $this->auth->verify($token);
  }

  public function recuperarContrasena($correo) {
    return $this->auth->requestReset($correo);
  }

  public function restablecerContrasena($token, $nuevaContrasena) {
    return $this->auth->resetPass($token, $nuevaContrasena, $nuevaContrasena);
  }

  public function iniciar($correo, $contrasena) {
    $resultado = $this->auth->login($correo, $contrasena);

    if ($resultado['error']) {
      return ['exito' => false, 'mensaje' => $resultado['message']];
    }

    $_SESSION['usuario'] = $resultado['hash'];
    return ['exito' => true, 'mensaje' => 'Inicio de sesión exitoso'];
  }

  public function cerrar() {
    if (isset($_SESSION['usuario'])) {
      session_start();
      session_unset();
      session_destroy();
      $this->auth->logout($_SESSION['usuario']);
      unset($_SESSION['usuario']);
    }
  }

  public function estaAutenticado() {
    session_start();
    return isset($_SESSION['usuario_id']);
  }

  public function obtenerUsuario() {
    session_start();
    return $_SESSION['usuario_id'] ?? null;
  }

}