<?php
namespace SamarioPHP\Aplicacion\Modelos;

class Perfil extends Modelo {

  public function usuario() {
    return $this->perteneceA(Usuario::class);
  }

}