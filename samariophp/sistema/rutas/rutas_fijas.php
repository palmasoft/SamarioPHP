<?php
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;
use Slim\Routing\RouteCollectorProxy;
use SamarioPHP\Aplicacion\Controladores\InstalacionControlador;
use SamarioPHP\Aplicacion\Controladores\WebControlador;
use SamarioPHP\Aplicacion\Controladores\AutenticacionControlador;
use SamarioPHP\Aplicacion\Controladores\AdminControlador;

return function ($aplicacion, $logger) {
  $logger->info('[RUTAS FIJAS] Registrando rutas principales...');

  // Rutas de instalación

  $aplicacion->get(RUTA_INSTALAR, [InstalacionControlador::class, 'mostrarInstalacion']);
  $aplicacion->post(RUTA_INSTALAR, [InstalacionControlador::class, 'ejecutarInstalacion']);

  // Ruta para la página web publica - el HOME
  $aplicacion->get(RUTA_INICIO, [WebControlador::class, 'mostrarInicio']);

  // Grupo de rutas para autenticación
//  $aplicacion->group(RUTA_USUARIO, function (RouteCollectorProxy $grupo) {
  // Registro
  $aplicacion->get(RUTA_USUARIO_REGISTRO, [WebControlador::class, 'mostrarFormularioRegistro']);
  $aplicacion->post(RUTA_USUARIO_REGISTRO, [AutenticacionControlador::class, 'procesarRegistro']);

  // Verificación de correo
  $aplicacion->get(RUTA_USUARIO_VERIFICACION, [AutenticacionControlador::class, 'verificarCorreoElectronico']);

  // Recuperación de contraseña
  $aplicacion->get(RUTA_USUARIO_RECUPERAR_CLAVE, [WebControlador::class, 'mostrarFormularioRecuperarClave']);

  // Inicio y cierre de sesión
  $aplicacion->get(RUTA_USUARIO_ENTRAR, [WebControlador::class, 'mostrarFormularioLogin']);
  $aplicacion->post(RUTA_USUARIO_ENTRAR, [AutenticacionControlador::class, 'procesarLogin']);
  $aplicacion->get(RUTA_ADMIN, [AdminControlador::class, 'mostrarPanelAdministracion'])
  //    ->middleware('autenticado'); 
  ;
//  });
  $aplicacion->get(RUTA_USUARIO_SALIR, [AutenticacionControlador::class, 'cerrarSesion']);
  $aplicacion->post(RUTA_USUARIO_SALIR, [AutenticacionControlador::class, 'cerrarSesion']);

  $logger->info('[RUTAS FIJAS] Rutas principales registradas.');
};
