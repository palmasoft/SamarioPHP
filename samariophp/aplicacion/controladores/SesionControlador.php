<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace SamarioPHP\Controladores;
use SamarioPHP\Ayudas\GestorLog;
use Psr\Http\Message\ResponseInterface as Respuesta;
use Psr\Http\Message\ServerRequestInterface as Peticion;

/**
 * Description of SesionControlador
 *
 * @author SISTEMAS
 */
class SesionControlador extends Controlador {
  function mostrarInicioSesion(Peticion $peticion, Respuesta $respuesta) {
    GestorLog::log('aplicacion', 'info', '[AUTENTICACION] Mostrando página de login', ['campos' => 'valores']);
    $contenido = $this->plantillas->render('autenticacion/login.html.php');
    $respuesta->getBody()->write($contenido);
    return $respuesta;
  }

  function validarDatosUsuario(Peticion $peticion, Respuesta $respuesta) {

    GestorLog::log('aplicacion', 'info', '[AUTENTICACION] Procesando inicio de sesión');
    $datos = $peticion->getParsedBody();
    $correo = $datos['correo'] ?? '';
    $contrasena = $datos['contrasena'] ?? '';

    try {
      $usuario = New \Usuario($correo, $contrasena);

      if (count($usuario) > 0 && password_verify($contrasena, $usuario[0]['contrasena'])) {
        session_start();
        $_SESSION['usuario_id'] = $usuario[0]['id'];
        $_SESSION['usuario_correo'] = $usuario[0]['correo'];

// Redirigir al inicio o a una página de perfil
        return $respuesta->withRedirect(RUTA_INICIO);
      } else {
        $loggerAplicacion->warning('[AUTENTICACION] Credenciales inválidas');
        return $respuesta->withRedirect('/login')->withStatus(401);
      }
    } catch (Exception $e) {
      $loggerAplicacion->error('[AUTENTICACION] Error al intentar iniciar sesión: ' . $e->getMessage());
      return $respuesta->withRedirect('/login')->withStatus(500);
    }
  }

  public function iniciarSesion() {
    // Verificar si el usuario ya está autenticado
    if ($this->sesion->estaAutenticado()) {
      return $this->redirigir('/dashboard');
    }

    // Procesar la petición POST para iniciar sesión
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $correo = $_POST['correo'];
      $contrasena = $_POST['contrasena'];

      // Buscar el usuario en la base de datos
      $usuario = Usuario::buscarPorCorreo($correo);

      // Verificar contraseña
      if ($usuario && password_verify($contrasena, $usuario->contrasena)) {
        // Iniciar sesión
        $this->sesion->iniciar($usuario->id);
        return $this->redirigir('/dashboard');
      } else {
        return $this->mostrarVista('login', ['error' => 'Credenciales inválidas']);
      }
    }

    return $this->mostrarVista('login');
  }

  public function cerrarSesion(Peticion $peticion, Respuesta $respuesta) {
    $logger->info('[AUTENTICACION] Cerrando sesión');

// Lógica para cerrar la sesión (eliminar datos de sesión)
    session_start();
    session_unset();
    session_destroy();

    return $respuesta->withRedirect(RUTA_INICIO); // Redirige a la página de inicio o login
    // Cerrar sesión
    $this->sesion->cerrar();
    return $this->redirigir('/login');
  }
}