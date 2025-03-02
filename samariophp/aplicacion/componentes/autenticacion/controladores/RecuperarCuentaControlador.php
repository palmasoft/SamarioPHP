<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace SamarioPHP\Aplicacion\Controladores;

/**
 * Description of RecuperarCuentaControlador
 *
 * @author SISTEMAS
 */
class RecuperarCuentaControlador extends Controlador {

    //put your code here
    /**
     * Muestra el formulario de recuperación de contraseña
     */
    public function mostrarFormularioRecuperarClave() {
        return vista('autenticacion.recuperar_contrasena');
    }

    /**
     * Procesa la recuperación de contraseña
     */
    public function procesarRecuperarClave() {
        $respuestaRecuperar = $this->enviarCorreoRecuperacionClave($this->correo);
        if ($respuestaRecuperar->tipo === 'error') {
            return vista('autenticacion.recuperar_contrasena', compact($respuestaRecuperar));
        }
        redirigir(RUTA_USUARIO_ENTRAR);
    }

    /**
     * Envía un correo para recuperación de contraseña
     */
    public function enviarCorreoRecuperacionClave($correo) {
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

}