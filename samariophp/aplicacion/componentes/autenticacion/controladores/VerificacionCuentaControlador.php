<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace SamarioPHP\Aplicacion\Controladores;

/**
 * Description of VerificacionCuentaControlador
 *
 * @author SISTEMAS
 */
class VerificacionCuentaControlador extends Controlador {

//    /**
//     * Muestra la vista de verificación de correo electrónico
//     */
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

}