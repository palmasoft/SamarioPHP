<?php
namespace SamarioPHP\Aplicacion\Correos;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Correos {

    protected $asunto;
    protected $destinatarios = [];
    protected $datos = [];
    protected $plantilla;
    protected $contenido;
    protected $adjuntos = [];

    /**
     * Establece la plantilla y procesa el contenido con los datos.
     *
     * @param string $plantilla
     * @param array $datos
     */
    public function establecerPlantilla($plantilla, $datos = []) {
        $this->plantilla = $plantilla;
        $this->datos = $datos;
        $this->procesarPlantilla();
    }

    /**
     * Procesa la plantilla con Twig y los datos recibidos.
     */
    protected function procesarPlantilla() {
        $rutaPlantilla = DIR_APP . "componente/modulo/correos/" . str_replace('.', '/', $this->plantilla) . ".correo.php";
        $twig = $this->obtenerTwig();
        $this->contenido = $twig->render($rutaPlantilla, $this->datos);
    }

    /**
     * Establece el asunto del correo.
     *
     * @param string $asunto
     * @param array $datos
     */
    public function establecerAsunto($asunto, $datos = []) {
        $this->asunto = $this->procesarAsunto($asunto, $datos);
    }

    /**
     * Procesa el asunto con los datos utilizando Twig.
     */
    protected function procesarAsunto($asunto, $datos) {
        $twig = $this->obtenerTwig();
        return $twig->createTemplate($asunto)->render($datos);
    }

    /**
     * Añade un destinatario al correo.
     *
     * @param string $correo
     * @param string|null $nombre
     */
    public function agregarDestinatario($correo, $nombre = null) {
        $this->destinatarios[] = [$correo, $nombre];
    }

    /**
     * Añade un archivo adjunto al correo.
     *
     * @param string $rutaArchivo
     */
    public function agregarAdjunto($rutaArchivo) {
        if (file_exists($rutaArchivo)) {
            $this->adjuntos[] = $rutaArchivo;
        }
    }

    /**
     * Envía el correo.
     *
     * @param string $destinatario
     * @param array $datos
     * @return bool
     */
    public static function enviar($destinatario, $datos) {
        $correo = new static();
        $correo->agregarDestinatario($destinatario);
        $correo->establecerAsunto($correo->asunto, $datos);
        $correo->procesarPlantilla();
        return $correo->procesarEnvio();
    }

    /**
     * Procesa el envío del correo.
     *
     * @return bool
     */
    protected function procesarEnvio() {
        $servicioCorreo = new CorreoElectronicoServicio();
        return $servicioCorreo->enviar($this->destinatarios, $this->asunto, $this->contenido, $this->adjuntos);
    }

    /**
     * Devuelve el estado del correo para depuración.
     *
     * @return array
     */
    public function obtenerEstado() {
        return [
            'asunto' => $this->asunto,
            'destinatarios' => $this->destinatarios,
            'contenido' => $this->contenido,
            'adjuntos' => $this->adjuntos,
        ];
    }

    /**
     * Obtiene la instancia de Twig.
     *
     * @return Environment
     */
    protected function obtenerTwig() {
        $loader = new FilesystemLoader(DIR_APP . 'vistas/');
        return new Environment($loader);
    }

}