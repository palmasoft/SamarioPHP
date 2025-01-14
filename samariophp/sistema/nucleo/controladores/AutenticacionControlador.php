<?php
namespace SamarioPHP\Aplicacion\Controladores;

use SamarioPHP\Aplicacion\Servicios\Autenticacion;
use SamarioPHP\Aplicacion\Servicios\CorreoElectronico;
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;

class AutenticacionControlador extends Controlador {

  /**
   * Muestra el formulario de registro
   */
  public function mostrarFormularioRegistro() {
    return $this->renderizar('autenticacion/registro');
  }

  /**
   * Procesa el registro de un usuario
   */
  public function procesarRegistro() {
    if ($this->tieneDatos(['nombre', 'correo', 'contrasena', 'recontrasena'])) {
      $datos = array_merge($this->datos, ['error' => "Todos los campos son requeridos"]);
      return $this->renderizar('autenticacion/registro', $datos);
    }

    $resultado = $this->registrarUsuario($this->correo, $this->contrasena, $this->recontrasena, $this->datos);
    if ($resultado['error']) {
      return $this->renderizar('autenticacion/registro', ['error' => $resultado['message']]);
    }
    // Mostrar mensaje de espera de verificación
    return $this->mostrarVistaRegistroCompletado($this->correo);
  }

  /**
   * Registra un nuevo usuario y envía un correo de verificación
   */
  public function registrarUsuario($correo, $contrasena, $rcontrasena, $params = []) {
    try {

      if ($contrasena !== $rcontrasena) {
        throw new \Exception("Las contraseñas no coinciden.");
      }

      $Usuario = $this->sesion->registrar($correo, $contrasena, $params['nombre'] ?? null);
      $enviado = $this->enviarCorreoVerificacion($Usuario);

      if ($enviado) {
        return ['error' => false, 'message' => 'Usuario registrado y correo enviado'];
      } else {
        return ['error' => false, 'message' => 'Usuario registrado y pero no fue enviado el correo de verificacion'];
      }
    } catch (\Exception $e) {
      return ['error' => true, 'message' => $e->getMessage()];
    }
  }

  /**
   * Envía un correo de verificación
   */
  public function enviarCorreoVerificacion($Usuario) {

    $enlace = "{$this->config['aplicacion']['url_base']}" . RUTA_USUARIO_VERIFICACION . "?token={$Usuario->token_verificacion}";

    $Correo = new \Correo('autenticacion/correo_verificacion', [
        'nombre' => $Usuario->nombre,
        'enlace_verificacion' => $enlace,
        'nombre_proyecto' => $this->config['aplicacion']['nombre'],
        'anio' => date('Y'),
    ]);
    $Correo->asunto = "Verificación de correo [{$Usuario->correo}] - {$this->config['aplicacion']['nombre']}";
    $Correo->destinatario($Usuario->correo, $Usuario->nombre);
    return $Correo->enviar();
  }

  /**
   * Muestra la vista de registro completado
   */
  public function mostrarVistaRegistroCompletado($correo) {
    return $this->renderizar('autenticacion/registro_completado', [
            'correo' => $correo
    ]);
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
  /**
   * Verifica el correo electrónico
   */
  /**
   * Verifica el correo electrónico
   */
  public function verificarCorreoElectronico() {
    try {
      $this->token = \GestorHTTP::parametro('token');

      // Validar token y verificar el correo
      $resultado = $this->actualizarVerficacionCorreo($this->token);

      if ($resultado['error']) {
        // Token no válido o expirado
        return $this->renderizar('autenticacion/verificar_correo_error', ['mensaje' => $resultado['message'] ?? 'No se pudo verificar el correo.']);
      }

      if ($resultado['correo_verificado']) {
        return $this->renderizar('autenticacion/verificar_correo_error', ['mensaje' => 'El correo ya estaba verificado previamente.']);
      }

      // Verificación exitosa
      return $this->renderizar('autenticacion/verificar_correo_exito', ['mensaje' => 'Correo verificado exitosamente. ¡Gracias por confirmar tu dirección!']);
    } catch (\Exception $e) {
      // Manejar errores inesperados
      return $this->renderizar('autenticacion/verificar_correo_error', ['mensaje' => $e->getMessage() ?? 'Ocurrió un error al verificar tu correo. Inténtalo más tarde.']);
    }
  }

  public function actualizarVerficacionCorreo($token) {
    try {
      // Validar que se haya proporcionado un token
      if (empty((string) $token)) {
        throw new \Exception('Token no proporcionado.');
      }

      // Intentar verificar el correo con el token
      $Usuario = $this->sesion->verificarCorreo($token);

      if ($Usuario) {
        return ['error' => false, 'correo_verificado' => false, 'message' => 'Correo del Usuario verificado.'];
      }

      return ['error' => true, 'message' => 'No se pudo verificar el correo.'];
    } catch (\Exception $e) {
      return ['error' => true, 'message' => $e->getMessage()];
    }
  }

  //
  ///
  ////
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
  ///
  ///////
  /**
   * Muestra el formulario de login
   */
  public function mostrarFormularioLogin() {
    return $this->renderizar('autenticacion/login');
  }

  /**
   * Procesa el inicio de sesión
   */
  public function procesarLogin() {
    $correo = $this->obtenerParametro('correo');
    $contrasena = $this->obtenerParametro('contrasena');
    $resultado = $this->autenticacion->iniciarSesion($correo, $contrasena);

    if ($resultado['error']) {
      return $this->renderizar('autenticacion/login', ['error' => $resultado['message']]);
    }

    $_SESSION['usuario'] = $resultado['hash'];
    return $this->redirigir(RUTA_USUARIOS_INICIO);
  }

  /**
   * Muestra el formulario de recuperación de contraseña
   */
  public function mostrarFormularioRecuperarClave() {
    return $this->renderizar('autenticacion/recuperar_contrasena');
  }

  /**
   * Procesa la recuperación de contraseña
   */
  public function procesarRecuperarClave(HTTPSolicitud $peticion) {
    $correo = $peticion->getParsedBody()['correo'];
    $resultado = $this->recuperarContrasena($correo);

    if ($resultado['error']) {
      return $this->renderizar('autenticacion/recuperar_contrasena', ['error' => $resultado['message']]);
    }

    return $this->redirigir(RUTA_USUARIO_ENTRAR);
  }

  /**
   * Envía un correo para recuperación de contraseña
   */
  public function recuperarContrasena($correo) {
    try {
      $token = $this->autenticacion->recuperarContrasena($correo);
      $asunto = "Recuperación de contraseña";
      $cuerpo = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='https://tudominio.com/restablecer?token={$token}'>Restablecer contraseña</a>";
      $this->correos->enviarCorreo($correo, $asunto, $cuerpo);

      return ['error' => false, 'message' => 'Correo de recuperación enviado'];
    } catch (\Exception $e) {
      return ['error' => true, 'message' => $e->getMessage()];
    }
  }

  /**
   * Cierra la sesión del usuario
   */
  public function cerrarSesion() {
    session_start();
    session_unset();
    session_destroy();
    return $this->redirigir(RUTA_USUARIO_ENTRAR);
  }

}