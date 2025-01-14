<?php

class Correo {

  private $vista;
  private $datos;
  private $correos;
  private $aplicacion;
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
    $this->correos = $GLOBALS['enviador_correos'];
  }

  public function enviar() {
    try {
      $this->correos->enviarCorreo($this->para, $this->asunto, $this->mensaje);
      return $this->correos;
    } catch (\PHPMailer\PHPMailer\Exception $e) {
      return "Error al enviar correo: {$this->correos->ErrorInfo}";
    }
  }

  public function destinatario($correo, $nombre = '') {
    $this->para = [$correo, $nombre];
  }

}