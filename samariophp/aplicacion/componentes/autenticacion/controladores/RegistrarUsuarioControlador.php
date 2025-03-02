<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace SamarioPHP\Aplicacion\Controladores;

use SamarioPHP\Sistema\Auth;

/**
 * Description of UsuarioRegistradoControlador
 *
 * @author SISTEMAS
 */
class RegistrarUsuarioControlador extends Controlador {

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
            return vista(VISTA_USUARIO_REGISTRO, ['error' => "Todos los campos son requeridos"]);
        }

        if ($this->datos['contrasena'] !== $this->datos['recontrasena']) {
            return vista(VISTA_USUARIO_REGISTRO, ['error' => "Las contraseñas no coinciden"]);
        }

        $respuesta = $this->registrarUsuario($this->datos['correo'], $this->datos['contrasena'], $this->datos);
        if ($respuesta->tipo === 'error') {
            return vista(VISTA_USUARIO_REGISTRO, ['error' => $respuesta->mensaje]);
        }
        return vista('autenticacion.registro_completado', ['error' => $respuesta->mensaje]);
    }

    /**
     * Registra un nuevo usuario y envía un correo de verificación
     */
    public function registrarUsuario($correo, $contrasena, $params = []) {
        try {

            if (Auth::existeUsuario($correo)) {
                return error('El Correo yá está registrado.');
            }
//            // Intentamos registrar el usuario
            $respuestaRegistro = Auth::registrar($correo, $contrasena, $params['nombre'] ?? null);
            if ($respuestaRegistro->tipo === 'exito') {
                $Usuario = $respuestaRegistro->datos['Usuario'];
                $correoEnviado = $this->enviarCorreoVerificacion($Usuario = new \Usuario());
                return exito(
                    $correoEnviado ? 'Usuario registrado y correo enviado.' : 'Usuario registrado, pero no se pudo enviar el correo de verificación.'
                );
            }
            return error($respuestaRegistro->mensaje ?? 'Error desconocido en el registro');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    /**
     * Envía un correo de verificación
     */
    public function enviarCorreoVerificacion($Usuario) {
//        $enlace = config('url_base') . "" . RUTA_USUARIO_VERIFICACION . "?token={$Usuario->token_verificacion}";
//        return VerificacionCorreo::enviar([$Usuario->correo, $Usuario->nombre], compact($Usuario, $enlace));
        return false;
    }

    /**
     * Muestra la vista de registro completado
     */
    public function mostrarVistaRegistroCompletado($respuesta) {
        return vista('autenticacion.registro_completado');
    }

}