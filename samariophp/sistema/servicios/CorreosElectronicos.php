<?php
// archivo: app/samariophp/Servicios/CorreosElectronicos.php
namespace App\Servicios;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CorreosElectronicos {

  private $mail;

  public function __construct() {
    $this->mail = new PHPMailer(true);
    $this->mail->isSMTP();
    $this->mail->Host = 'smtp.tu-servidor.com';
    $this->mail->SMTPAuth = true;
    $this->mail->Username = 'tu-email@dominio.com';
    $this->mail->Password = 'tu-clave';
    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $this->mail->Port = 587;
  }

  public function enviarCorreo($email, $asunto, $cuerpo) {
    try {
      $this->mail->setFrom('no-reply@tu-dominio.com', 'Tu Aplicación');
      $this->mail->addAddress($email);
      $this->mail->isHTML(true);
      $this->mail->Subject = $asunto;
      $this->mail->Body = $cuerpo;
      $this->mail->send();
    } catch (Exception $e) {
      // Manejar el error aquí
      echo "Error al enviar el correo: {$this->mail->ErrorInfo}";
    }
  }
}