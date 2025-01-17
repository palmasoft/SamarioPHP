<?php
define('DIR_SYS', __DIR__ . DIRECTORY_SEPARATOR);

// Definir constantes para las rutas
define('DIR_SPHP', DIR_SYS . '/samariophp/');
define('DIR_APP', DIR_SPHP . '/aplicacion/');
define('DIR_CONTROLADORES', DIR_APP . '/controladores/');
define('DIR_MODELOS', DIR_APP . '/modelos');
define('DIR_VISTAS', DIR_APP . '/vistas/html/');
define('DIR_CORREOS', DIR_APP . '/correos/');
define('DIR_APP_SYS', DIR_SPHP . '/sistema/');
define('DIR_ALMACEN', DIR_SPHP . '/almacen/');

//RUTAS DEL FRAMEWORK
define('RUTA_LIBRERIAS', DIR_SPHP . 'autoload.php');
define('RUTA_INSTALADOR', DIR_APP_SYS . 'instalador.php');
define('RUTA_ENRUTADOR', DIR_APP_SYS . 'rutas/enrutador.php');

//CONFIGURACIONES
define('RUTA_CONFIGURACION', DIR_APP_SYS . 'configuracion.php');
define('DIR_CONFIGURACIONES', DIR_APP_SYS . 'configuraciones/');
define('RUTA_CONFIG_BASEDEDATOS', DIR_CONFIGURACIONES . 'basededatos.php');
define('RUTA_CONFIG_VALIDACION', DIR_CONFIGURACIONES . 'validacion.php');
define('RUTA_CONFIG_ERRORES', DIR_CONFIGURACIONES . 'errores.php');
define('RUTA_CONFIG_LOGS', DIR_CONFIGURACIONES . 'logs.php');
define('RUTA_CONFIG_PHINX', DIR_CONFIGURACIONES . 'phinx.php');
define('RUTA_CONFIG_TWIG', DIR_CONFIGURACIONES . 'twig.php');
define('RUTA_CONFIG_SLIM', DIR_CONFIGURACIONES . 'slim.php');
define('RUTA_CONFIG_MEEDO', DIR_CONFIGURACIONES . 'medoo.php');

//MIDDLEWARE
define('RUTA_MANTENIMIENTO', DIR_APP_SYS . 'middleware/mantenimiento.php');

//LOGS
define('RUTA_LOGS', DIR_ALMACEN . 'logs/');

//BASES DE DATOS
define('DIR_BASE_DATOS', DIR_SPHP . 'base_de_datos/');
define('DIR_ESQUEMAS', DIR_BASE_DATOS . 'esquemas/');
define('RUTA_ESQUEMA_INICIAL', DIR_ESQUEMAS . 'esquema_inicial.php');
define('RUTA_ESQUEMA_AUDITORIA', DIR_ESQUEMAS . 'esquema_auditoria.php');
define('DIR_MIGRACIONES', DIR_BASE_DATOS . 'migraciones/');
define('RUTA_GENERAR_MIGRACIONES_MODELOS', DIR_BASE_DATOS . 'generador/GeneradorMigracionesModelos.php');

// Definir constantes para las vistas fijas o inicials
define('VISTA_EXTENSION', '.html.php');
define('VISTA_404', '404'.VISTA_EXTENSION);

// Definir rutas generales
//
//
define('RUTA_INICIO', '/');   // Ruta para la página de inicio
define('VISTA_INICIO', 'web/inicio'); 
//
//
define('RUTA_INSTALAR', '/instalacion');  // Ruta para la instalación
define('VISTA_INSTALACION', 'instalacion/preparacion'.VISTA_EXTENSION);
define('VISTA_INSTALACION_TERMINADA', 'instalacion/completada'.VISTA_EXTENSION);
//
//
// gestion de autenticacion
define('RUTA_USUARIO_REGISTRO', '/registro');
define('VISTA_USUARIO_REGISTRO', 'autenticacion/registro');
define('RUTA_USUARIO_RECUPERAR_CLAVE', '/recuperar-clave');
define('RUTA_USUARIO_VERIFICACION', '/verificar-correo');
//
define('RUTA_USUARIO_ENTRAR', '/inicio-sesion');
define('VISTA_USUARIO_ENTRAR', 'autenticacion/login');
//
define('RUTA_ADMIN', '/panel-principal');   // Ruta para la página de inicio
define('VISTA_ADMIN', 'admin/panel/principal');
define('RUTA_USUARIO_INICIO', '/panel-principal');
define('VISTA_USUARIO_INICIO', 'admin/panel/principal');

define('RUTA_USUARIO_SALIR', '/cerrar-sesion');

define('RUTA_USUARIOS', '/usuarios');  // Ruta para los usuarios
define('RUTA_HELLO', '/hello/{name}'); // Ruta para el saludo
define('RUTA_TEST', '/test'); // Ruta para pruebas
