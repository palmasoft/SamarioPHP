<?php

class Correo {

  private $vista;
  private $datos;
  private $enviador;
  private $plantillas;
  public $asunto;
  public $para;
  protected $mensaje;

  public function __construct($vista, $datos = []) {
    $this->vista = $vista;
    $this->datos = $datos;

    // Inicializar Twig    
    $this->plantillas = $GLOBALS['plantillas'];
    // Renderizar el mensaje al inicializar
    $this->mensaje = $this->plantillas->render($this->vista . VISTA_EXTENSION, $this->datos);

    // Inicializar PHPMailer
    $this->enviador = $GLOBALS['enviador_correos'];
  }

  public function enviar() {
    try {
      $this->enviador->enviarCorreo($this->para, $this->asunto, $this->mensaje);
      return $this->enviador;
    } catch (\PHPMailer\PHPMailer\Exception $e) {
      return "Error al enviar correo: {$this->enviador->ErrorInfo}";
    }
  }

  public function destinatario($correo, $nombre = '') {
    $this->para = [$correo, $nombre];
  }

  public function asunto($asunto) {
    $this->asunto = $asunto;
  }

  public function mensaje($mensaje) {
    $this->mensaje = $mensaje;
  }

}