<?php
namespace SamarioPHP\Controladores;
use SamarioPHP\Ayudas\GestorLog;
use Psr\Http\Message\ResponseInterface as Respuesta;
use Psr\Http\Message\ServerRequestInterface as Peticion;

class AutenticacionControlador extends Controlador {
//
  function mostrarRegistro(Peticion $peticion, Respuesta $respuesta) {
    GestorLog::log('aplicacion', 'info', '[AUTENTICACION] Mostrando página de registro');
    $contenido = $this->plantillas->render('autenticacion/registro.html.php');
    $respuesta->getBody()->write($contenido);
    return $respuesta;
  }

  function guardarDatosUsuario(Peticion $peticion, Respuesta $respuesta) {
    GestorLog::log('aplicacion', 'info', '[AUTENTICACION] Procesando registro');

// Aquí puedes manejar la lógica de registro (validar los datos y crear un nuevo usuario)
// Procesar la petición POST para registrar un nuevo usuario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nombre = $_POST['nombre'];
      $correo = $_POST['correo'];
      $contrasena = $_POST['contrasena'];

// Validar datos del usuario (puedes agregar más validaciones)
      if (empty($nombre) || empty($correo) || empty($contrasena)) {
        return $this->mostrarVista('registro', ['error' => 'Todos los campos son requeridos']);
      }

// Crear un nuevo usuario
      $usuario = new Usuario();
      $usuario->nombre = $nombre;
      $usuario->correo = $correo;
      $usuario->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
      $usuario->guardar();

// Redirigir a la página de inicio de sesión
      return $this->redirigir('/login');
    }

    return $this->mostrarVista('registro');

    $datos = $peticion->getParsedBody();
    $correo = $datos['correo'] ?? '';
    $contrasena = $datos['contrasena'] ?? '';

// Crear el nuevo usuario en la base de datos

    return $respuesta->withRedirect(RUTA_INICIO); // O redirigir a una página de éxito
  }

  function enviarCorreoVerificacion($email, $token) {
    $mailer = new Mailer();
    $asunto = 'Verificación de Correo';
    $cuerpo = 'Haz clic en el siguiente enlace para verificar tu correo: <a href="https://tu-dominio.com/verificar?token=' . $token . '">Verificar Correo</a>';
    $mailer->enviarCorreo($email, $asunto, $cuerpo);
  }

  function verificarCorreoElectronico(Peticion $peticion, Respuesta $respuesta, array $args) {
    $token = $args['token'];

// Verificar token en la base de datos y activar usuario
    try {
      $usuario = $baseDeDatos->select('usuarios', ['id', 'verificado'], ['token_verificacion' => $token]);

      if (count($usuario) > 0 && !$usuario[0]['verificado']) {
// Activar cuenta
        $baseDeDatos->update('usuarios', ['verificado' => 1], ['id' => $usuario[0]['id']]);
        $contenido = $this->plantillas->render('autenticacion/verificar_correo.html.php');
      } else {
        $contenido = $this->plantillas->render('autenticacion/error_verificacion.html.php');
      }

      $respuesta->getBody()->write($contenido);
      return $respuesta;
    } catch (Exception $e) {
      $logger->error('[AUTENTICACION] Error al verificar correo: ' . $e->getMessage());
      return $respuesta->withStatus(500);
    }
  }

  function mostrarRecuperarClave(Peticion $peticion, Respuesta $respuesta) {
    GestorLog::log('aplicacion', 'info', '[AUTENTICACION] Mostrando formulario para recuperar contraseña');
    $contenido = $this->plantillas->render('autenticacion/recuperar_contrasena.html.php');
    $respuesta->getBody()->write($contenido);
    return $respuesta;
  }
}