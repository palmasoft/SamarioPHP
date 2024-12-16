<?php
namespace SamarioPHP\Controladores;
use SamarioPHP\Ayudas\GestorLog;
use Psr\Http\Message\ResponseInterface as Respuesta;
use Psr\Http\Message\ServerRequestInterface as Peticion;

class AutenticacionControlador extends Controlador {
  //
  function mostrarInicioSesion(Peticion $peticion, Respuesta $respuesta) {
    GestorLog::log('aplicacion', 'info', '[AUTENTICACION] Mostrando página de login', ['campos' => 'valores']);
    $contenido = $this->plantillas->render('autenticacion/login.html.php', ["config" => $GLOBALS['config']]);
    $respuesta->getBody()->write($contenido);
    return $respuesta;
  }

//
//  function validarDatosUsuario(Peticion $peticion, Respuesta $respuesta) {
//    global $loggerAplicacion, $BaseDeDatos, $plantillas;
//    $loggerAplicacion->info('[AUTENTICACION] Procesando inicio de sesión');
//    $datos = $peticion->getParsedBody();
//    $correo = $datos['correo'] ?? '';
//    $contrasena = $datos['contrasena'] ?? '';
//
//// Lógica para verificar las credenciales
//    try {
//      $usuario = $BaseDeDatos->select('usuarios', ['id', 'correo', 'contrasena'], ['correo' => $correo]);
//
//      if (count($usuario) > 0 && password_verify($contrasena, $usuario[0]['contrasena'])) {
//        session_start();
//        $_SESSION['usuario_id'] = $usuario[0]['id'];
//        $_SESSION['usuario_correo'] = $usuario[0]['correo'];
//
//// Redirigir al inicio o a una página de perfil
//        return $respuesta->withRedirect(RUTA_INICIO);
//      } else {
//        $loggerAplicacion->warning('[AUTENTICACION] Credenciales inválidas');
//        return $respuesta->withRedirect('/login')->withStatus(401);
//      }
//    } catch (Exception $e) {
//      $loggerAplicacion->error('[AUTENTICACION] Error al intentar iniciar sesión: ' . $e->getMessage());
//      return $respuesta->withRedirect('/login')->withStatus(500);
//    }
//  }
//
//  function enviarCorreoVerificacion($email, $token) {
//    $mailer = new Mailer();
//    $asunto = 'Verificación de Correo';
//    $cuerpo = 'Haz clic en el siguiente enlace para verificar tu correo: <a href="https://tu-dominio.com/verificar?token=' . $token . '">Verificar Correo</a>';
//    $mailer->enviarCorreo($email, $asunto, $cuerpo);
//  }
//
//  public function iniciarSesion() {
//    // Verificar si el usuario ya está autenticado
//    if ($this->sesion->estaAutenticado()) {
//      return $this->redirigir('/dashboard');
//    }
//
//    // Procesar la petición POST para iniciar sesión
//    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//      $correo = $_POST['correo'];
//      $contrasena = $_POST['contrasena'];
//
//      // Buscar el usuario en la base de datos
//      $usuario = Usuario::buscarPorCorreo($correo);
//
//      // Verificar contraseña
//      if ($usuario && password_verify($contrasena, $usuario->contrasena)) {
//        // Iniciar sesión
//        $this->sesion->iniciar($usuario->id);
//        return $this->redirigir('/dashboard');
//      } else {
//        return $this->mostrarVista('login', ['error' => 'Credenciales inválidas']);
//      }
//    }
//
//    return $this->mostrarVista('login');
//  }
//
//  public function registrarUsuario() {
//    // Procesar la petición POST para registrar un nuevo usuario
//    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//      $nombre = $_POST['nombre'];
//      $correo = $_POST['correo'];
//      $contrasena = $_POST['contrasena'];
//
//      // Validar datos del usuario (puedes agregar más validaciones)
//      if (empty($nombre) || empty($correo) || empty($contrasena)) {
//        return $this->mostrarVista('registro', ['error' => 'Todos los campos son requeridos']);
//      }
//
//      // Crear un nuevo usuario
//      $usuario = new Usuario();
//      $usuario->nombre = $nombre;
//      $usuario->correo = $correo;
//      $usuario->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
//      $usuario->guardar();
//
//      // Redirigir a la página de inicio de sesión
//      return $this->redirigir('/login');
//    }
//
//    return $this->mostrarVista('registro');
//  }
//
//  public function cerrarSesion() {
//    // Cerrar sesión
//    $this->sesion->cerrar();
//    return $this->redirigir('/login');
//  }
}