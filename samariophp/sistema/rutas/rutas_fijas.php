<?php
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Respuesta;
use Psr\Http\Message\ServerRequestInterface as Peticion;
return function ($aplicacion, $logger) {
  $logger->info('[RUTAS FIJAS] Registrando rutas principales...');
  // Ruta para la página de inicio
  $aplicacion->get(RUTA_INICIO, [new \SamarioPHP\Controladores\InicioControlador(), 'mostrarInicio']);

  // Verificar si ya está instalado
  $archivoInstalacion = RUTA_LOGS . '/instalacion_completa.php'; // Ajusta la ruta si es necesario
  if (file_exists($archivoInstalacion)) {
    // Si ya está instalado, redirigir al índice
    $aplicacion->get(RUTA_INSTALAR, function (Peticion $peticion, Respuesta $respuesta, $args) use ($aplicacion) {
      return $respuesta->withRedirect(RUTA_INICIO);  // Redirige a la página de inicio
    });
  } else {
    // Grupo de instalación
    $aplicacion->group(RUTA_INSTALAR, function (RouteCollectorProxy $grupo) {
      $grupo->get('', [\SamarioPHP\Controladores\InstalacionControlador::class, 'mostrarInstalacion']);
      $grupo->post('', [\SamarioPHP\Controladores\InstalacionControlador::class, 'ejecutarInstalacion']);
    });
  }

  // Autenticación
  $aplicacion->get(
      RUTA_USUARIO_REGISTRO,
      [\SamarioPHP\Controladores\AutenticacionControlador::class, 'mostrarRegistro']
  );
  $aplicacion->post(
      RUTA_USUARIO_REGISTRO,
      [\SamarioPHP\Controladores\AutenticacionControlador::class, 'guardarDatosUsuario']
  );
  //// Ruta para verificar el correo electrónico
  $aplicacion->get(
      RUTA_USUARIO_VERFICACION,
      [\SamarioPHP\Controladores\AutenticacionControlador::class, 'verificarCorreoElectronico']
  );
  $aplicacion->get(
      RUTA_USUARIO_RECUPERAR_CLAVE,
      [\SamarioPHP\Controladores\AutenticacionControlador::class, 'mostrarRecuperarClave']
  );

  // Sesion
  $aplicacion->get(
      RUTA_USUARIO_ENTRAR,
      [\SamarioPHP\Controladores\SesionControlador::class, 'mostrarInicioSesion']
  );
  $aplicacion->post(
      RUTA_USUARIO_ENTRAR,
      [\SamarioPHP\Controladores\SesionControlador::class, 'validarDatosUsuario']
  );
  $aplicacion->post(
      RUTA_USUARIO_SALIR,
      [\SamarioPHP\Controladores\SesionControlador::class, 'cerrarSesion']
  );

  $logger->info('[RUTAS FIJAS] Rutas principales registradas.');
};
