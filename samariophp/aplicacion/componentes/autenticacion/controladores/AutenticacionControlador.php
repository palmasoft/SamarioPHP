<?php
namespace SamarioPHP\Aplicacion\Controladores;

use SamarioPHP\Aplicacion\Servicios\Autenticacion;
use SamarioPHP\Aplicacion\Servicios\CorreoElectronico;
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;

class AutenticacionControlador extends Controlador {

  private $token;

  /**
   * Procesa el registro de un usuario
   */
  public function procesarRegistro() {
    if ($this->faltanDatos(['nombre', 'correo', 'contrasena', 'recontrasena'])) {
      $datos = array_merge($this->datos, ['error' => "Todos los campos son requeridos"]);
      return $this->renderizar(VISTA_USUARIO_REGISTRO, $datos);
    }

    $resultado = $this->registrarUsuario($this->correo, $this->contrasena, $this->recontrasena, $this->datos);
    if ($resultado['error']) {
      return $this->renderizar(VISTA_USUARIO_REGISTRO, ['error' => $resultado['message']]);
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

      $Usuario = $this->autenticacion->registrar($correo, $contrasena, $params['nombre'] ?? null);
      if ($Usuario) {
        $enviado = $this->enviarCorreoVerificacion($Usuario);
        if ($enviado) {
          return ['error' => false, 'message' => 'Usuario registrado y correo enviado'];
        } else {
          return ['error' => false, 'message' => 'Usuario registrado y pero no fue enviado el correo de verificacion'];
        }
      } else {
        return ['error' => false, 'message' => 'Usuario NO registrado'];
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
        'correo_contacto' => $this->config['aplicacion']['correo_contacto'],
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

      return $this->renderizar('autenticacion/verificar_correo_exito', ['mensaje' => 'Correo verificado exitosamente. ¡Gracias por confirmar tu dirección!']);
    } catch (\Exception $e) {
      // Manejar errores inesperados
      return $this->renderizar('autenticacion/verificar_correo_error', ['mensaje' => $e->getMessage() ?? 'Ocurrió un error al verificar tu correo. Inténtalo más tarde.']);
    }
  }

  public function actualizarVerficacionCorreo($token) {
    try {
      if (empty($token)) {
        throw new \Exception('Token no proporcionado.');
      }

      $Usuario = $this->autenticacion->verificarCorreo($token);
      if ($Usuario) {
        $this->enviarCorreoBienvenida($Usuario);
        return ['error' => false, 'correo_verificado' => false, 'message' => 'Correo del Usuario verificado.', 'Usuario' => $Usuario];
      }

      return ['error' => true, 'message' => 'No se pudo verificar el correo.'];
    } catch (\Exception $e) {
      return ['error' => true, 'message' => $e->getMessage()];
    }
  }

  /**
   * Envía un correo de bienvenida después de verificar el correo
   */
  public function enviarCorreoBienvenida($Usuario) {

    $Correo = new \Correo('bienvenida', [
        'nombre' => $Usuario->nombre,
        'nombre_proyecto' => $this->config['aplicacion']['nombre'],
        'anio' => date('Y'),
        'url_base' => $this->config['aplicacion']['url_base'],
        'correo_contacto' => $this->config['aplicacion']['correo_contacto'],
    ]);
    $Correo->asunto = "¡Bienvenido a {$this->config['aplicacion']['nombre']}!";
    $Correo->destinatario($Usuario->correo, $Usuario->nombre);
    return $Correo->enviar();
  }

  /**
   * Envía un correo notificando un fallo en la verificación del correo
   */
  public function enviarCorreoErrorVerificacion($Usuario) {

    $Correo = new \Correo('autenticacion/correo_error_verificacion', [
        'nombre' => $Usuario->nombre,
        'nombre_proyecto' => $this->config['aplicacion']['nombre'],
        'anio' => date('Y'),
        'url_soporte' => "{$this->config['aplicacion']['url_base']}/soporte",
        'correo_contacto' => $this->config['aplicacion']['correo_contacto'],
    ]);
    $Correo->asunto = "Problema con la verificación de tu correo - {$this->config['aplicacion']['nombre']}";
    $Correo->destinatario($Usuario->correo, $Usuario->nombre);
    return $Correo->enviar();
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
    if ($this->faltanDatos(['correo', 'contrasena'])) {
      $datos = array_merge($this->datos, ['error' => "Todos los campos son requeridos."]);
      return $this->renderizar(VISTA_USUARIO_ENTRAR, $datos);
    }
    // Validar la contraseña
    $resultado = $this->autenticacion->validarCredenciales($this->correo, $this->contrasena);
    if ($resultado['error']) {
      return $this->renderizar(VISTA_USUARIO_ENTRAR, ['error' => $resultado['message']]);
    }
    $this->sesion->iniciar($resultado['Usuario']);
    return $this->redirigir(RUTA_ADMIN);
  }

  //
  //
  //
  //
  //
  //
  //  
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
    $this->sesion->cerrar();
    return $this->redirigir(RUTA_USUARIO_ENTRAR);
  }


  // Acción para mostrar la página de inicio

}