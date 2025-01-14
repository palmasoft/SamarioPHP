<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('SAMARIOPHP_INICIO', microtime(true));
require_once __DIR__ . '/samariophp/booteador.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
  // Configuración del servidor
  $mail->isSMTP();
  $mail->Host = $configuracion['enviador_correos']['smtp']['host']; // Cambia esto por tu servidor SMTP
  $mail->SMTPAuth = $configuracion['enviador_correos']['smtp']['auth'];
  $mail->Username = $configuracion['enviador_correos']['smtp']['username']; // Tu usuario SMTP
  $mail->Password = $configuracion['enviador_correos']['smtp']['password']; // Tu contraseña SMTP
  $mail->SMTPSecure = $configuracion['enviador_correos']['smtp']['secure']; // O `PHPMailer::ENCRYPTION_STARTTLS` según tu configuración
  $mail->Port = $configuracion['enviador_correos']['smtp']['port']; // Usa 587 para STARTTLS o 465 para SSL
  $mail->SMTPDebug = $configuracion['enviador_correos']['debug'];
  
  // Remitente y destinatario
  $mail->setFrom($configuracion['enviador_correos']['email_from'], $configuracion['aplicacion']['nombre']);
  $mail->
  print_r($configuracion); 
  $mail->addAddress('ing.llinasramirez@gmail.com', 'Nombre del Destinatario');
//
//  // Contenido del correo
  $mail->isHTML(true);
  $mail->Subject = 'Correo de prueba desde PHPMailer';
  $mail->Body = '<p>Este es un correo de prueba enviado desde <b>PHPMailer</b>.</p>';
  $mail->AltBody = 'Este es un correo de prueba enviado desde PHPMailer.';

  // Enviar correo
  $mail->send();
  echo 'Correo enviado correctamente';
} catch (Exception $e) {
  echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
