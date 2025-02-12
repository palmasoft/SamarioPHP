<?php
namespace SamarioPHP\Aplicacion\Servicios;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Servicio para el envío de correos electrónicos utilizando PHPMailer.
 */
class CorreoElectronicoServicio {

    protected $mailer;
    protected $config;
    protected $app;
    public $plantilla = 'ejemplo';
    public $remitenteNOMBRE;
    public $remitenteCORREO;
    public $responderNOMBRE;
    public $responderCORREO;

    /**
     * Constructor del servicio de correo.
     */
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->app = config('aplicacion');
        $this->config = config('enviador_correos');
        $this->configurar();
    }

    /**
     * Configura el objeto PHPMailer con los ajustes del sistema.
     */
    private function configurar() {
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->config['smtp']['host'];
        $this->mailer->Port = $this->config['smtp']['port'];
        $this->mailer->SMTPSecure = $this->config['smtp']['secure'];
        $this->mailer->SMTPAuth = $this->config['smtp']['auth'];
        $this->mailer->Username = $this->config['smtp']['username'];
        $this->mailer->Password = $this->config['smtp']['password'];
        $this->mailer->CharSet = $this->config['email_charset'];
        $this->mailer->Encoding = $this->config['email_codificacion'];
        $this->mailer->SMTPDebug = $this->config['debug'];
    }

    /**
     * Envía un correo a un destinatario.
     *
     * @param string $destinatario Correo electrónico del destinatario.
     * @param string $asunto Asunto del correo.
     * @param string $mensaje Contenido del correo en formato HTML.
     * @param array $adjuntos Lista de archivos adjuntos.
     * @param array $conCopia Lista de direcciones en copia oculta (BCC).
     * @return \Respuesta
     */
    public function enviar($destinatario, $asunto, $mensaje, array $adjuntos = [], array $conCopia = []) {
        try {
            $this->prepararCorreo($asunto, $mensaje, $adjuntos);
            $this->configurarDirecciones();
            $this->cargarDestinatarios([[$destinatario, null]], $conCopia);

            $this->mailer->send();
            return \Respuesta::exito('Correo enviado exitosamente');
        } catch (Exception $e) {
            return \Respuesta::error('Error al enviar el correo: ' . $e->getMessage());
        }
    }

    /**
     * Prepara el contenido del correo y adjunta archivos si es necesario.
     *
     * @param string $asunto Asunto del correo.
     * @param string $mensaje Contenido del correo en formato HTML.
     * @param array $adjuntos Lista de archivos adjuntos.
     */
    private function prepararCorreo($asunto, $mensaje, array $adjuntos = []) {
        $this->mailer->Subject = mb_convert_encoding($asunto ?: 'Sin asunto', 'UTF-8', 'auto');
        $this->mailer->Body = $mensaje;
        $this->mailer->AltBody = \Utilidades::convertirHtmlATexto($mensaje);

        foreach ($adjuntos as $archivo) {
            if (file_exists($archivo)) {
                $this->mailer->addAttachment($archivo);
            }
        }
    }

    /**
     * Carga los destinatarios principales y en copia oculta.
     */
    private function cargarDestinatarios(array $destinatarios, array $conCopia = []) {
        foreach ($destinatarios as [$correo, $nombre]) {
            $this->mailer->addAddress($correo, $nombre ?? '');
        }
        foreach ($conCopia as [$correo, $nombre]) {
            $this->mailer->addBCC($correo, $nombre ?? '');
        }
    }

    /**
     * Configura las direcciones del remitente y de respuesta.
     */
    private function configurarDirecciones() {
        $this->mailer->setFrom($this->remitenteCORREO ?? $this->config['email_enviadopor'], $this->remitenteNOMBRE ?? $this->app['nombre']);
        $this->mailer->addReplyTo($this->responderCORREO ?? $this->config['email_respondera'], $this->responderNOMBRE ?? $this->app['nombre']);
    }

    /**
     * Envía un correo estático a un destinatario.
     */
    public static function enviarCorreo($destinatario, $asuntoCorreo, $cuerpoMensaje, array $archivosAdjuntos = [], array $conCopia = []) {
        $servicioCorreo = new self();
        return $servicioCorreo->enviar($destinatario, $asuntoCorreo, $cuerpoMensaje, $archivosAdjuntos, $conCopia);
    }

    /**
     * Envía un correo a una lista de contactos (BCC).
     */
    public static function enviarListado(array $listadoContactos, $asuntoCorreo, $cuerpoMensaje, array $archivosAdjuntos = []) {
        $servicioCorreo = new self();
        $servicioCorreo->prepararCorreo($asuntoCorreo, $cuerpoMensaje, $archivosAdjuntos);
        $servicioCorreo->configurarDirecciones();
        $servicioCorreo->cargarDestinatarios([], $listadoContactos);

        try {
            $servicioCorreo->mailer->send();
            return \Respuesta::exito('Correo enviado a todos los contactos.');
        } catch (Exception $e) {
            return \Respuesta::error('Error al enviar el correo a listado de contactos: ' . $e->getMessage());
        }
    }

    /**
     * Envía correos de forma individual a una lista de contactos.
     */
    public static function enviarMasivo(array $listadoContactos, $asuntoCorreo, $cuerpoMensaje, array $archivosAdjuntos = []) {
        $servicioCorreo = new self();

        foreach ($listadoContactos as [$correo, $nombre]) {
            $servicioCorreo->mailer->clearAddresses();
            try {
                $servicioCorreo->enviar($correo, $asuntoCorreo, $cuerpoMensaje, $archivosAdjuntos);
            } catch (Exception $e) {
                \Logger::error("Error al enviar correo a {$correo}: " . $e->getMessage());
            }
        }

        return \Respuesta::exito('Correos enviados a todos los contactos de forma individual.');
    }

}