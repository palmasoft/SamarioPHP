<?php

// app/correos/Correo.php
class CorreoModelo {

  protected $para;
  protected $asunto;
  protected $mensaje;

  public function establecerDestinatario($para) {
    $this->para = $para;
  }

  public function establecerAsunto($asunto) {
    $this->asunto = $asunto;
  }

  public function establecerMensaje($mensaje) {
    $this->mensaje = $mensaje;
  }

  public function enviar() {
    mail($this->para, $this->asunto, $this->mensaje);
  }
}