<?php
namespace SamarioPHP\Aplicacion\Controladores;

use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;

class AutenticacionControlador extends Controlador {

  //para los nuevos  
  public function mostrarFormularioRegistro() {
    return $this->renderizar('autenticacion/registro');
  }

  public function procesarRegistro(HTTPSolicitud $peticion, HTTPRespuesta $respuesta) {

    $datos = $peticion->getParsedBody();
    print_r($datos);
    die();
    $resultado = $this->sesionControlador->registrarUsuario($correo, $contrasena, $recontrasena, $params = []);

    if ($resultado['error']) {
      return $this->renderizar('autenticacion/registro', ['error' => $resultado['message']]);
    }

//    $this->enviarCorreoVerificacion($correo, $resultado['token']);
    return $this->redirigir('/login');

    GestorLog::log('aplicacion', 'info', '[AUTENTICACION] Procesando registro');
    $datos = $peticion->getParsedBody();
    $nombre = $datos['nombre'] ?? '';
    $correo = $datos['correo'] ?? '';
    $contrasena = $datos['contrasena'] ?? '';

    if (empty($nombre) || empty($correo) || empty($contrasena)) {
      return $respuesta->withStatus(400)->write("Todos los campos son requeridos");
    }

    $usuario = new \Usuario();
    $usuario->nombre = $nombre;
    $usuario->correo = $correo;
    $usuario->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    $usuario->guardar();

    return $this->redirigir('/login');
  }

  public function enviarCorreoVerificacion($correo, $token) {
    $mailer = new \PHPMailer();
    $asunto = 'Verificación de Correo';
    $mensaje = "Haz clic en el siguiente enlace para verificar tu cuenta: " .
        "<a href='{$this->config['url_base']}/verificar?token={$token}'>Verificar cuenta</a>";
    $mailer->enviarCorreo($correo, $asunto, $mensaje);
  }

  function verificarCorreoElectronico(HTTPSolicitud $peticion, HTTPRespuesta $respuesta) {
    $token = $args['token'];

// Verificar token en la base de datos y activar usuario
    try {
      $usuario = $baseDeDatos->select('usuarios', ['id', 'verificado'], ['token_verificacion' => $token]);

      if (count($usuario) > 0 && !$usuario[0]['verificado']) {
// Activar cuenta
        $baseDeDatos->update('usuarios', ['verificado' => 1], ['id' => $usuario[0]['id']]);
        $contenido = $this->plantillas->render('autenticacion/verificar_correo');
      } else {
        $contenido = $this->plantillas->render('autenticacion/error_verificacion');
      }

      $respuesta->getBody()->write($contenido);
      return $respuesta;
    } catch (Exception $e) {
      $logger->error('[AUTENTICACION] Error al verificar correo: ' . $e->getMessage());
      return $respuesta->withStatus(500);
    }
  }

  //para los ya registrados
  //iniciar
  public function mostrarFormularioLogin(HTTPSolicitud $peticion, HTTPRespuesta $respuesta) {
    $this->respuesta = $respuesta;
    return $this->renderizar('autenticacion/login');
  }

  public function procesarLogin() {

    $correo = \GestorHTTP::parametro('correo');
    $contrasena = \GestorHTTP::parametro('contrasena');
    $resultado = $this->sesionControlador->iniciarSesion($correo, $contrasena);

    if ($resultado['error']) {
      return $this->renderizar('autenticacion/login', ['error' => $resultado['message']]);
    }

    $_SESSION['usuario'] = $resultado['hash'];
    return $this->redirigir('/dashboard');

    GestorLog::log('aplicacion', 'info', '[AUTENTICACION] Iniciando sesión');
    $datos = $peticion->getParsedBody();
    $correo = $datos['correo'] ?? '';
    $contrasena = $datos['contrasena'] ?? '';

    // Verificar si el usuario ya está autenticado
    if ($this->sesion->estaAutenticado()) {
      return $this->redirigir('/dashboard');
    }

    $usuario = \Usuario::buscarPorCorreo($correo);
    if (!$usuario || !password_verify($contrasena, $usuario->contrasena)) {
      return $respuesta->withStatus(401)->write("Credenciales inválidas");
    }


    $controlador = new AutenticacionControlador();
    $respuesta = $controlador->iniciarSesion($correo, $contrasena);
    echo json_encode($respuesta);
    try {
      $usuario = New \Usuario($correo, $contrasena);

      if (count($usuario) > 0 && password_verify($contrasena, $usuario[0]['contrasena'])) {
        session_start();
        $_SESSION['usuario_id'] = $usuario[0]['id'];
        $_SESSION['usuario_correo'] = $usuario[0]['correo'];

// Redirigir al inicio o a una página de perfil
        return $respuesta->withRedirect(RUTA_INICIO);
      } else {
        $loggerAplicacion->warning('[AUTENTICACION] Credenciales inválidas');
        return $respuesta->withRedirect('/login')->withStatus(401);
      }
    } catch (Exception $e) {
      $loggerAplicacion->error('[AUTENTICACION] Error al intentar iniciar sesión: ' . $e->getMessage());
      return $respuesta->withRedirect('/login')->withStatus(500);
    }

    $_SESSION['usuario_id'] = $usuario->id;
    return $this->redirigir('/dashboard');
  }

  //salir
  public function cerrarSesion(HTTPSolicitud $peticion, HTTPRespuesta $respuesta) {


    if (isset($_SESSION['usuario'])) {
      $this->sesionControlador->cerrarSesion($_SESSION['usuario']);
      unset($_SESSION['usuario']);
    }
    return $this->redirigir('/login');

    // Cerrar sesión
    $this->sesion->cerrar();

    GestorLog::log('aplicacion', 'info', '[AUTENTICACION] Sesión cerrada');
    return $this->redirigir('/');
  }

  //gestion
  function mostrarRecuperarClave(HTTPSolicitud $peticion, HTTPRespuesta $respuesta) {
    GestorLog::log('aplicacion', 'info', '[AUTENTICACION] Mostrando formulario para recuperar contraseña');
    $contenido = $this->plantillas->render('autenticacion/recuperar_contrasena');
    $respuesta->getBody()->write($contenido);
    return $respuesta;
  }

}