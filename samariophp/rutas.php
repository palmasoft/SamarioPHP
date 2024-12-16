<?php
use Slim\Routing\RouteCollectorProxy;
// Gestion de Peticiones y Respuestas HTTP//
use Psr\Http\Message\ResponseInterface as Respuesta;
use Psr\Http\Message\ServerRequestInterface as Peticion;
// Configuración de las rutas
return function ($aplicacion, $configuracion, $baseDeDatos, $plantillas, $logger) {
  $logger->info('[RUTAS] Registrando las rutas principales...');
// Ruta para la página de inicio
  $aplicacion->get(RUTA_INICIO, [new \SamarioPHP\Controladores\InicioControlador(), 'mostrarInicio']);

// Ruta para la instalación
  $aplicacion->group(RUTA_INSTALAR, function (RouteCollectorProxy $grupo) {
    $grupo->get('', [\SamarioPHP\Controladores\InstalacionControlador::class, 'mostrarInstalacion']);
    $grupo->post('', [\SamarioPHP\Controladores\InstalacionControlador::class, 'ejecutarInstalacion']);
  });

//  Rutas para autenticación
// Ruta para la vista de inicio de sesión
  $aplicacion->get(RUTA_USUARIO_LOGIN, function (Peticion $peticion, Respuesta $respuesta) use ($plantillas, $logger) {
    return $respuesta->withRedirect(RUTA_USUARIO_ENTRAR); // Redirige a /autenticacion/login si es necesario
  });
  $aplicacion->get(RUTA_USUARIO_ENTRAR, [\SamarioPHP\Controladores\AutenticacionControlador::class, 'mostrarInicioSesion']);
// Ruta para VERIFICACION DE DATOS DE inicio de sesión
  $aplicacion->post(RUTA_USUARIO_ENTRAR, [\SamarioPHP\Controladores\AutenticacionControlador::class, 'validarDatosUsuario']);

// Ruta para la vista de registro
  $aplicacion->get(RUTA_USUARIO_REGISTRO, function (Peticion $peticion, Respuesta $respuesta) use ($plantillas, $logger) {
    $logger->info('[AUTENTICACION] Mostrando página de registro');
    $contenido = $plantillas->render('autenticacion/registro.html.php');
    $respuesta->getBody()->write($contenido);
    return $respuesta;
  });

// Ruta para manejar el registro de usuario
  $aplicacion->post(RUTA_USUARIO_REGISTRO, function (Peticion $peticion, Respuesta $respuesta) use ($logger) {
    $logger->info('[AUTENTICACION] Procesando registro');

// Aquí puedes manejar la lógica de registro (validar los datos y crear un nuevo usuario)
    $datos = $peticion->getParsedBody();
    $correo = $datos['correo'] ?? '';
    $contrasena = $datos['contrasena'] ?? '';

// Crear el nuevo usuario en la base de datos

    return $respuesta->withRedirect(RUTA_INICIO); // O redirigir a una página de éxito
  });

// Ruta para cerrar sesión
  $aplicacion->get('/logout', function (Peticion $peticion, Respuesta $respuesta) use ($plantillas, $logger) {
    return $respuesta->withRedirect(RUTA_USUARIO_SALIR); // Redirige a /autenticacion/login si es necesario
  });

  $aplicacion->get(RUTA_USUARIO_SALIR, function (Peticion $peticion, Respuesta $respuesta) use ($logger) {
    $logger->info('[AUTENTICACION] Cerrando sesión');

// Lógica para cerrar la sesión (eliminar datos de sesión)
    session_start();
    session_unset();
    session_destroy();

    return $respuesta->withRedirect(RUTA_INICIO); // Redirige a la página de inicio o login
  });

// Ruta para mostrar el formulario de recuperación de contraseña
  $aplicacion->get(RUTA_USUARIO_RECUPERAR_CLAVE, function (Peticion $peticion, Respuesta $respuesta) use ($baseDeDatos, $plantillas, $logger) {
    $logger->info('[AUTENTICACION] Mostrando formulario para recuperar contraseña');
    $contenido = $plantillas->render('autenticacion/recuperar_contrasena.html.php');
    $respuesta->getBody()->write($contenido);
    return $respuesta;
  });

// Ruta para verificar el correo electrónico
  $aplicacion->get(RUTA_USUARIO_VERFICACION, function (Peticion $peticion, Respuesta $respuesta, array $args) use ($baseDeDatos, $logger) {
    $token = $args['token'];

// Verificar token en la base de datos y activar usuario
    try {
      $usuario = $baseDeDatos->select('usuarios', ['id', 'verificado'], ['token_verificacion' => $token]);

      if (count($usuario) > 0 && !$usuario[0]['verificado']) {
// Activar cuenta
        $baseDeDatos->update('usuarios', ['verificado' => 1], ['id' => $usuario[0]['id']]);
        $contenido = $plantillas->render('autenticacion/verificar_correo.html.php');
      } else {
        $contenido = $plantillas->render('autenticacion/error_verificacion.html.php');
      }

      $respuesta->getBody()->write($contenido);
      return $respuesta;
    } catch (Exception $e) {
      $logger->error('[AUTENTICACION] Error al verificar correo: ' . $e->getMessage());
      return $respuesta->withStatus(500);
    }
  });

  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // 
  // Rutas para Adminstracion de Usuarios
  $aplicacion->group(RUTA_USUARIOS, function (RouteCollectorProxy $grupo) {
    $grupo->get('', function (Peticion $peticion, Respuesta $respuesta) {
      // Lógica para listar usuarios
      $respuesta->getBody()->write('Lista de usuarios');
      return $respuesta;
    });

    $grupo->post('/crear', function (Peticion $peticion, Respuesta $respuesta) {
      // Lógica para crear un usuario
      $respuesta->getBody()->write('Crear un usuario');
      return $respuesta;
    });
  });

  // 
  // 
  // 
  // 
  // 
  // Otras rutas
  $aplicacion->get(RUTA_HELLO, function (Peticion $peticion, Respuesta $respuesta, array $args) {
    $name = $args['name'];
    $respuesta->getBody()->write("Hello, $name");
    return $respuesta;
  });

  $aplicacion->get(RUTA_TEST, function (Peticion $peticion, Respuesta $respuesta) {
    $respuesta->getBody()->write('Pruebas del sistema');
    return $respuesta;
  });

  $logger->info('Terminamos de registrar todas las rutas.');
};
