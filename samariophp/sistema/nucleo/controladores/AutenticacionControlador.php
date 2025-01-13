<?php
namespace SamarioPHP\Aplicacion\Controladores;

use SamarioPHP\Aplicacion\Servicios\Autenticacion;
use SamarioPHP\Aplicacion\Servicios\CorreoElectronico;
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;

class AutenticacionControlador extends Controlador {

  //
  //
  //
  //
  //
  //
  //
  //
  //para los nuevos  
  public function mostrarFormularioRegistro() {
    return $this->renderizar('autenticacion/registro');
  }

  public function procesarRegistro() {

    print_r($this->datos);
    echo "      ";
    print_r($this->nombre);
    echo "      ";
    print_r(is_null($this->nombre));
    echo "      ";
    print_r($this->correo);
    print_r($this->contrasena);
    print_r($this->recontrasena);

    if ($this->tieneDatos(["nombre", "correo", "contrasena", "recontrasena"])) {
      $datos = array_merge($this->datos, ['error' => "Todos los campos son requeridos"]);
      return $this->renderizar('autenticacion/registro', $datos);
    }

    $resultado = $this->registrarUsuario($this->correo, $this->contrasena, $this->recontrasena, $this->datos);
    if ($resultado['error']) {
      return $this->renderizar('autenticacion/registro', ['error' => $resultado['message']]);
    }

    // Redirigir al login
    return $this->redirigir('/login');
  }

  // Registra el usuario y envía el correo de verificación
  public function registrarUsuario($correo, $contrasena, $rcontrasena, $params = []) {
    try {
      if ($contrasena !== $rcontrasena) {
        throw new \Exception("Las contraseñas no coinciden.");
      }

      $token = $this->sesion->registrar($correo, $contrasena, $params['nombre'] ?? null);
      $this->enviarCorreoVerificacion($correo, $token);

      return ['error' => false, 'message' => 'Usuario registrado y correo enviado'];
    } catch (\Exception $e) {
      return ['error' => true, 'message' => $e->getMessage()];
    }
  }

  public function enviarCorreoVerificacion($correo, $token) {
    // Enviar correo de verificación
    $asunto = "Verifica tu correo";
    $cuerpo = "Haz clic en el siguiente enlace para verificar tu cuenta: <a href='https://tudominio.com/verificar?token={$token}'>Verificar cuenta</a>";
    return $this->correos->enviarCorreo($correo, $asunto, $cuerpo);
  }

  // Verificar el correo electrónico
  public function verificarCorreoElectronico(HTTPSolicitud $peticion, HTTPRespuesta $respuesta) {
    $token = $peticion->getAttribute('token');
    $resultado = $this->sesion->verificarCorreo($token);

    if ($resultado['error']) {
      return $this->renderizar('autenticacion/error_verificacion');
    }

    return $this->renderizar('autenticacion/verificar_correo');
  }

  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // Muestra el formulario de login
  public function mostrarFormularioLogin() {
    return $this->renderizar('autenticacion/login');
  }

  public function procesarLogin() {

    $correo = \GestorHTTP::parametro('correo');
    $contrasena = \GestorHTTP::parametro('contrasena');
    $resultado = $this->autenticacion->iniciarSesion($correo, $contrasena);

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

  // 
  // Inicia sesión de un usuario
  public function iniciarSesion($correo, $contrasena) {
    try {
      $resultado = $this->autenticacion->iniciarSesion($correo, $contrasena);
      if (!$resultado['exito']) {
        return ['error' => true, 'message' => 'Credenciales inválidas'];
      }

      return ['error' => false, 'hash' => $resultado['hash']];
    } catch (\Exception $e) {
      return ['error' => true, 'message' => $e->getMessage()];
    }
  }

  //
  //
  //
  //
  //
  //
  // Recuperar contraseña
  public function mostrarFormularioRecuperarClave(HTTPSolicitud $peticion, HTTPRespuesta $respuesta) {
    return $this->renderizar('autenticacion/recuperar_contrasena');
  }

  //
  public function procesarRecuperarClave(HTTPSolicitud $peticion, HTTPRespuesta $respuesta) {
    $correo = $peticion->getParsedBody()['correo'];
    $resultado = $this->recuperarContrasena($correo);

    if ($resultado['error']) {
      return $this->renderizar('autenticacion/recuperar_contrasena', ['error' => $resultado['message']]);
    }

    return $this->redirigir('/login');
  }

  //
  // Envía el correo de recuperación de contraseña
  public function recuperarContrasena($correo) {
    try {
      $token = $this->autenticacion->recuperarContrasena($correo);

      $asunto = "Recuperación de contraseña";
      $cuerpo = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='https://tudominio.com/restablecer?token={$token}'>Restablecer contraseña</a>";
      $this->correoServicio->enviarCorreo($correo, $asunto, $cuerpo);

      return ['error' => false, 'message' => 'Correo de recuperación enviado'];
    } catch (\Exception $e) {
      return ['error' => true, 'message' => $e->getMessage()];
    }
  }

  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // Cerrar sesión
  public function cerrarSesion(HTTPSolicitud $peticion, HTTPRespuesta $respuesta) {
    session_start();
    if (isset($_SESSION['usuario'])) {
      session_unset();
      session_destroy();
    }

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

}