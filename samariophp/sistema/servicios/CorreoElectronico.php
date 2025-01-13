<?php
namespace SamarioPHP\Aplicacion\Servicios;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CorreoElectronico {

  protected $mailer;

  public function __construct(PHPMailer $mailer = null) {
    $this->mailer = $mailer ?? new PHPMailer();
    $this->configurar();
  }

  private function configurar() {
    $this->mailer->isSMTP();
    $this->mailer->Host = 'smtp.tuservidor.com'; // Servidor SMTP
    $this->mailer->SMTPAuth = true;
    $this->mailer->Username = 'tu_usuario'; // Usuario SMTP
    $this->mailer->Password = 'tu_contraseña'; // Contraseña SMTP
    $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $this->mailer->Port = 587; // Puerto SMTP
    $this->mailer->setFrom('no-reply@tudominio.com', 'Tu Proyecto'); // Remitente
  }

  public function enviarCorreo($destinatario, $asunto, $cuerpo, $esHTML = true) {
    try {
      $this->mailer->addAddress($destinatario);
      $this->mailer->Subject = $asunto;
      $this->mailer->Body = $cuerpo;
      $this->mailer->isHTML($esHTML);

      if (!$this->mailer->send()) {
        throw new \Exception('Error al enviar el correo: ' . $this->mailer->ErrorInfo);
      }

      return ['exito' => true, 'mensaje' => 'Correo enviado exitosamente'];
    } catch (Exception $e) {
      return ['exito' => false, 'mensaje' => $e->getMessage()];
    }
  }

}