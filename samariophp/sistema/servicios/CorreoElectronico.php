<?php
namespace SamarioPHP\Aplicacion\Servicios;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CorreoElectronico {

  protected $mailer;
  protected $app;
  private $config;
  public static $remitenteNOMBRE = null;
  public static $remitenteCORREO = null;
  public static $responderNOMBRE = null;
  public static $responderCORREO = null;

  public function __construct($configuracion = null) {
    $this->mailer = new PHPMailer(true);
    $this->app = $configuracion['aplicacion'];
    $this->config = $configuracion['enviador_correos'];
    $this->configurar();
  }

  private function configurar() {

    $this->mailer->isSMTP();
    $this->mailer->Host = $this->config['smtp']['host']; // Servidor SMTP
    $this->mailer->Port = $this->config['smtp']['port']; // Puerto SMTP    
    $this->mailer->SMTPSecure = $this->config['smtp']['secure'];
    $this->mailer->SMTPAuth = $this->config['smtp']['auth'];
    $this->mailer->Username = $this->config['smtp']['username']; // Usuario SMTP
    $this->mailer->Password = $this->config['smtp']['password']; // Contraseña SMTP    

    $this->mailer->CharSet = $this->config['email_charset'];
    $this->mailer->Encoding = $this->config['email_codificacion'];
    $this->mailer->setFrom($this->config['email_enviadopor'], $this->app['nombre']); // Remitente

    $this->mailer->SMTPDebug = $this->config['debug'];
  }

  public function enviarCorreo($destinatario, $asunto, $cuerpo, $esHTML = true) {
    try {

      $this->mailer->isHTML($esHTML);
      $this->mailer->addAddress($destinatario[0], $destinatario[1] ?? "destinatario sin nombre" );
      $this->mailer->Subject = mb_convert_encoding($asunto, 'UTF-8', 'auto') ?? "sin asunto";
      $this->mailer->Body = $cuerpo;
      $this->mailer->AltBody = \Utilidades::convertirHtmlATexto($cuerpo);

      if (!$this->mailer->send()) {
        throw new \Exception('Error al enviar el correo: ' . $this->mailer->ErrorInfo);
      }

      return ['exito' => true, 'mensaje' => 'Correo enviado exitosamente'];
    } catch (Exception $e) {
      return ['exito' => false, 'mensaje' => $e->getMessage()];
    }
  }

//  
//  public static function enviar($correoCODIGO, array $contactoCorreo, $asuntoCorreo, $cuerpoMensaje, array $archivosAdjuntos = null, array $conCopiaA = null) {
//    $ConfigMAIL = self::prepararCorreo($correoCODIGO, $asuntoCorreo, $cuerpoMensaje, $archivosAdjuntos);
//    $ConfigMAIL->addAddress($contactoCorreo[0], $contactoCorreo[1]);
//    if (isset($conCopiaA) and!is_null($conCopiaA)) {
//      $ConfigMAIL->addBCC($conCopiaA[0], $conCopiaA[1]);
//    }
//    return self::gestionEnvio($ConfigMAIL);
//  }
//
//  public static function enviarListado($correoCODIGO, array $listadoContactos, $asuntoCorreo, $cuerpoMensaje, array $archivosAdjuntos = null) {
//    $ConfigMAIL = self::prepararCorreo($correoCODIGO, $asuntoCorreo, $cuerpoMensaje, $archivosAdjuntos);
//    foreach ($listadoContactos as $contactoCorreo) {
////           print_r($contactoCorreo);            
//      $ConfigMAIL->addAddress($contactoCorreo[0], $contactoCorreo[1]);
//    }
//    return self::gestionEnvio($ConfigMAIL);
//  }
//
//  private static function prepararCorreo($correoCODIGO, $asuntoCorreo, $cuerpoMensaje, array $archivosAdjuntos = null) {
//    $ConfigMAIL = Correos::configCorreo($correoCODIGO);
//    $ConfigMAIL->Subject = $asuntoCorreo;
//    $ConfigMAIL->Body = $cuerpoMensaje;
//    if (!empty($archivosAdjuntos)) {
//      foreach ($archivosAdjuntos as $archivoAdjunto) {
//        $ConfigMAIL->AddAttachment($archivoAdjunto);
//      }
//    }
//    return $ConfigMAIL;
//  }
//
//  private static function gestionEnvio($mailSENDER) {
//    if (!is_null(self::$remitenteCORREO)) {
//      $mailSENDER->setFrom(self::$remitenteCORREO, self::$remitenteNOMBRE);
//      $mailSENDER->addReplyTo(self::$remitenteCORREO, self::$remitenteNOMBRE);
//    } else {
//      $mailSENDER->setFrom('sicam32@ccsm.org.co', 'SICAM32 Notificaciones');
//      $mailSENDER->addReplyTo('centro.soporte@ccsm.org.co', 'SICAM32 Notificaciones');
//    }
//    if ($mailSENDER->send()) {
//      return true;
//    } else {
//      return RespuestasSistema::error(
//              'ERROR AL ENVIAR CORREO A LA DIRECCION  [' . self::$remitenteCORREO . '] ' . self::$remitenteNOMBRE . '  ' . $mailSENDER->ErrorInfo, '405'
//      );
//    }
//  }
//
//  private static function configCorreo($correoCODIGO = 'SICAM32') {
//    $ConfigMAIL = SMTPGoogle::configSICAM32();
//    if (empty($correoCODIGO) or MODO_EJECUCION == 'PRUEBAS') {
//      $correoCODIGO = 'SICAM32';
//    }
//    if (!empty($correoCODIGO)) {
//      $DatosCorreo = CuentasCorreo::configuracion($correoCODIGO);
//      if (!empty($DatosCorreo)) {
//        //echo " ..................  nueva confirguracion ......................";
//        $ConfigMAIL = new PHPMailer();
////      $ConfigMAIL->SMTPDebug = 4;
//        //                 $ConfigMAIL->isSMTP();
//        //                 $ConfigMAIL->isHTML(true);
//        //                 $ConfigMAIL->SMTPAuth = true;
//        //                 $ConfigMAIL->SMTPSecure = 'tls';
//        //                 $ConfigMAIL->Host = 'smtp.gmail.com';
//        //                 $ConfigMAIL->Port = 587;
//        //                 $ConfigMAIL->Username = 'sicam32@ccsm.org.co';
//        //                 $ConfigMAIL->Password = 'a2%G7DV*';
//        $ConfigMAIL->isSMTP($DatosCorreo->correoSMTP);
//        $ConfigMAIL->isHTML($DatosCorreo->correoHTML);
//        $ConfigMAIL->SMTPAuth = $DatosCorreo->correoAUTENTICARSE;
//        $ConfigMAIL->SMTPSecure = $DatosCorreo->correoSEGURIDAD;
//        $ConfigMAIL->Host = $DatosCorreo->correoSERVIDOR;
//        $ConfigMAIL->Port = $DatosCorreo->correoPUERTO;
//        $ConfigMAIL->Username = $DatosCorreo->correoUSUARIO;
//        $ConfigMAIL->Password = $DatosCorreo->correoCLAVE;
//
//        $ConfigMAIL->NombreRemitente = $DatosCorreo->correoREMITENTE;
//        $ConfigMAIL->CorreoRemitente = $DatosCorreo->correoUSUARIO;
//        $ConfigMAIL->CharSet = 'UTF-8';
//        $ConfigMAIL->preSend();
//      }
//    }
//    if (self::$modoENVIO == 'PRUEBAS') {
//      $ConfigMAIL->SMTPDebug = 4;
//    }
//    return $ConfigMAIL;
//  }

}