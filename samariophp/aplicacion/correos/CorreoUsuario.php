<?php

class CorreoUsuario extends Correo {
  public function enviarCorreoBienvenida($usuario) {
    $this->establecerDestinatario($usuario->correo);
    $this->establecerAsunto('¡Bienvenido!');
    $this->establecerMensaje('Hola, ' . $usuario->nombre);
    $this->enviar();
  }
}