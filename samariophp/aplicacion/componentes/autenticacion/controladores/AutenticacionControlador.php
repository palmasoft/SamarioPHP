<?php
namespace SamarioPHP\Aplicacion\Controladores;

use SamarioPHP\Sistema\Auth;
use SamarioPHP\Aplicacion\Correos\BienvenidaCorreo;

class AutenticacionControlador extends Controlador {

    private $token;

    /**
     * Muestra el formulario de registro
     */
    public function mostrarVistaRegistro() {
        return vista(VISTA_USUARIO_REGISTRO);
    }

    /**
     * Procesa el registro de un usuario
     */
    public function procesarRegistro() {
        if ($this->faltanDatos(['nombre', 'correo', 'contrasena', 'recontrasena'])) {
            $datos = array_merge($this->datos, ['error' => "Todos los campos son requeridos"]);
            return vista(VISTA_USUARIO_REGISTRO, $datos);
        }

        $respuesta = $this->registrarUsuario($this->correo, $this->contrasena, $this->recontrasena, $this->datos);
        if ($respuesta->tipo === 'error') {
            return vista(VISTA_USUARIO_REGISTRO, ['error' => $respuesta->mensaje]);
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

            // Se asume que el servicio de autenticación retorna una Respuesta
            $respuestaRegistro = Auth::registrar($correo, $contrasena, $params['nombre'] ?? null);
            if ($respuestaRegistro->tipo === 'exito') {
                $Usuario = $respuestaRegistro->datos['usuario'];
                $correoEnviado = $this->enviarCorreoVerificacion($Usuario);
                if ($correoEnviado) {
                    return exito('Usuario registrado y correo enviado');
                } else {
                    return exito('Usuario registrado, pero no se pudo enviar el correo de verificación');
                }
            } else {
                return error('Usuario no registrado');
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * Envía un correo de verificación
     */
    public function enviarCorreoVerificacion($Usuario) {
        $enlace = "{$this->config['aplicacion']['url_base']}" . RUTA_USUARIO_VERIFICACION
            . "?token={$Usuario->token_verificacion}";
        return VerificacionCorreo::enviar([$Usuario->correo, $Usuario->nombre], compact($Usuario, $enlace));
    }

    /**
     * Muestra la vista de registro completado
     */
    public function mostrarVistaRegistroCompletado($correo) {
        return vista('autenticacion/registro_completado', ['correo' => $correo]);
    }

    /**
     * Muestra la vista de verificación de correo electrónico
     */
    public function mostrarVistaVerificacionCorreo() {
        try {
            $this->token = \GestorHTTP::parametro('token');
            $respuestaVerificacion = $this->actualizarVerificacionCorreo($this->token);

            if ($respuestaVerificacion->tipo === 'error') {
                return vista('autenticacion/verificar_correo_error', ['mensaje' => $respuestaVerificacion->mensaje]);
            }
            if (isset($respuestaVerificacion->datos['correo_verificado']) && $respuestaVerificacion->datos['correo_verificado']) {
                return vista('autenticacion/verificar_correo_error', ['mensaje' => 'El correo ya estaba verificado previamente.']);
            }
            return vista('autenticacion/verificar_correo_exito', ['mensaje' => 'Correo verificado exitosamente. ¡Gracias por confirmar tu dirección!']);
        } catch (\Exception $e) {
            return vista('autenticacion/verificar_correo_error', ['mensaje' => $e->getMessage() ?: 'Ocurrió un error al verificar tu correo. Inténtalo más tarde.']);
        }
    }

    /**
     * Actualiza la verificación del correo electrónico usando el token
     */
    public function actualizarVerificacionCorreo($token) {
        try {
            if (empty($token)) {
                throw new \Exception('Token no proporcionado.');
            }

            $Usuario = Auth::verificarCorreo($token);
            if ($Usuario) {
                BienvenidaCorreo::enviar([$Usuario->correo, $Usuario->nombre], compact($Usuario));
                return exito('Correo del Usuario verificado.', [
                    'correo_verificado' => false,
                    'Usuario' => $Usuario
                ]);
            }
            return error('No se pudo verificar el correo.');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
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

    /**
     * Muestra el formulario de login
     */
    public function mostrarFormularioLogin() {
        return vista(VISTA_USUARIO_ENTRAR);
    }

    /**
     * Procesa el inicio de sesión
     */
    public function procesarLogin() {
        if ($this->faltanDatos(['correo', 'contrasena'])) {
            return vista(VISTA_USUARIO_ENTRAR, ['error' => "Todos los campos son requeridos."]);
        }
        // Se asume que Auth::validarCredenciales ahora retorna un objeto Respuesta
        $respuestaLogin = Auth::validarCredenciales($this->correo, $this->contrasena);
        if ($respuestaLogin->tipo === 'error') {
            return vista(VISTA_USUARIO_ENTRAR, ['error' => $respuestaLogin->mensaje]);
        }
        print_r($respuestaLogin->datos);
        redirigir(RUTA_ADMIN);
    }

    /**
     * Muestra el formulario de recuperación de contraseña
     */
    public function mostrarVistaRecuperarClave() {
        return vista('autenticacion.recuperar_contrasena');
    }

    /**
     * Procesa la recuperación de contraseña
     */
    public function procesarRecuperarClave() {
        $respuestaRecuperar = $this->recuperarContrasena($this->correo);
        if ($respuestaRecuperar->tipo === 'error') {
            return vista('autenticacion.recuperar_contrasena', compact($respuestaRecuperar));
        }
        redirigir(RUTA_USUARIO_ENTRAR);
    }

    /**
     * Envía un correo para recuperación de contraseña
     */
    public function recuperarContrasena($correo) {
        try {
            $respuestaRecuperacion = Auth::recuperarContrasena($correo);
            if ($respuestaRecuperacion->tipo === 'error') {
                return $respuestaRecuperacion;
            }
            $token = $respuestaRecuperacion->datos['token'] ?? null;
            $asunto = "Recuperación de contraseña";
            $cuerpo = "Haz clic en el siguiente enlace para restablecer tu contraseña: "
                . "<a href='https://tudominio.com/restablecer?token={$token}'>Restablecer contraseña</a>";
            $this->correos->enviarCorreo($correo, $asunto, $cuerpo);
            return exito('Correo de recuperación enviado');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * Cierra la sesión del usuario
     */
    public function cerrarSesion() {
        Auth::cerrarSesion();
        redirigir(RUTA_USUARIO_ENTRAR);
    }

}