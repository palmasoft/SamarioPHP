<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace SamarioPHP\Aplicacion\Correos;

class VerificacionCorreo extends Correos {

    public function __construct() {
        $this->asunto = "Verificación de Correo [{{Usuario.correo}}] - {{app.nombre}}";
        $this->plantilla = "autenticacion/correo_verificacion";
    }

}