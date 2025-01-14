<?php
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;
use Slim\Routing\RouteCollectorProxy;
use SamarioPHP\Aplicacion\Controladores\InstalacionControlador;
use SamarioPHP\Aplicacion\Controladores\WebControlador;
use SamarioPHP\Aplicacion\Controladores\AutenticacionControlador;

return function ($aplicacion, $logger) {
  $logger->info('[RUTAS FIJAS] Registrando rutas principales...');

  // Rutas de instalación
  $aplicacion->group(RUTA_INSTALAR, function (RouteCollectorProxy $grupo) {
    $grupo->get('', [InstalacionControlador::class, 'mostrarInstalacion']);
    $grupo->post('', [InstalacionControlador::class, 'ejecutarInstalacion']);
  });

  // Ruta para la página web publica - el HOME
  $aplicacion->get(RUTA_INICIO, [WebControlador::class, 'mostrarInicio']);
  
  // Grupo de rutas para autenticación
//  $aplicacion->group(RUTA_USUARIO, function (RouteCollectorProxy $grupo) {
  // Registro
  $aplicacion->get(RUTA_USUARIO_REGISTRO, [AutenticacionControlador::class, 'mostrarFormularioRegistro']);
  $aplicacion->post(RUTA_USUARIO_REGISTRO, [AutenticacionControlador::class, 'procesarRegistro']);

  // Verificación de correo
  $aplicacion->get(RUTA_USUARIO_VERIFICACION, [AutenticacionControlador::class, 'verificarCorreoElectronico']);

  // Recuperación de contraseña
  $aplicacion->get(RUTA_USUARIO_RECUPERAR_CLAVE, [AutenticacionControlador::class, 'mostrarFormularioRecuperarClave']);

  // Inicio y cierre de sesión
  $aplicacion->get(RUTA_USUARIO_ENTRAR, [AutenticacionControlador::class, 'mostrarFormularioLogin']);
  $aplicacion->post(RUTA_USUARIO_ENTRAR, [AutenticacionControlador::class, 'procesarLogin']);
  $aplicacion->post(RUTA_USUARIO_SALIR, [AutenticacionControlador::class, 'cerrarSesion']);
  $aplicacion->get(RUTA_USUARIO_INICIO, [WebControlador::class, 'mostrarPanelAdministracion']);
//  });

  $logger->info('[RUTAS FIJAS] Rutas principales registradas.');
};
