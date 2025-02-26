<?php
use Psr\Http\Message\ResponseInterface as HTTPRespuesta;
use Psr\Http\Message\ServerRequestInterface as HTTPSolicitud;
use Slim\Routing\RouteCollectorProxy;
use SamarioPHP\Aplicacion\Controladores\InstalacionControlador;
use SamarioPHP\Aplicacion\Controladores\WebControlador;
use SamarioPHP\Aplicacion\Controladores\AutenticacionControlador;
use SamarioPHP\Aplicacion\Controladores\AppControlador;

return function ($aplicacion) {

    // Rutas de instalación
    $aplicacion->get(RUTA_INSTALAR, [InstalacionControlador::class, 'mostrarInstalacion']);
    $aplicacion->post(RUTA_INSTALAR, [InstalacionControlador::class, 'ejecutarInstalacion']);

    // Ruta para la página web publica - el HOME
    $aplicacion->get(RUTA_INICIO, [WebControlador::class, 'mostrarInicio']);

    // Registro
    $aplicacion->get(RUTA_USUARIO_REGISTRO, [AutenticacionControlador::class, 'mostrarVistaRegistro']);
    //rcibir datos de usuario y registralo
    $aplicacion->post(RUTA_USUARIO_REGISTRO, [AutenticacionControlador::class, 'procesarRegistro']);
    // Verificación de correo
    $aplicacion->get(RUTA_USUARIO_VERIFICACION, [AutenticacionControlador::class, 'verificarCorreoElectronico']);
    // Recuperación de contraseña
    $aplicacion->get(RUTA_USUARIO_RECUPERAR_CLAVE, [AutenticacionControlador::class, 'mostrarFormularioRecuperarClave']);
    // Inicio y cierre de sesión
    $aplicacion->get(RUTA_USUARIO_ENTRAR, [AutenticacionControlador::class, 'mostrarFormularioLogin']);
    $aplicacion->post(RUTA_USUARIO_ENTRAR, [AutenticacionControlador::class, 'procesarLogin']);
    $aplicacion->get(RUTA_USUARIO_SALIR, [AutenticacionControlador::class, 'cerrarSesion']);
    $aplicacion->post(RUTA_USUARIO_SALIR, [AutenticacionControlador::class, 'cerrarSesion']);

    $aplicacion->get(RUTA_ADMIN, [AppControlador::class, 'mostrarPanelAdministracion']);
};
