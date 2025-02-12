<?php
namespace SamarioPHP\Aplicacion\Correos;

use SamarioPHP\Aplicacion\Correos\Correos;

class BienvenidaCorreo extends Correos {
    
    public function __construct() {
        $this->asunto = "Bienvenido al sistema, {{ usuario.nombre }}";
        $this->plantilla = "modulo.bienvenida";
    }

}
