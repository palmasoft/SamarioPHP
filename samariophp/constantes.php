<?php

// Definir constantes para las rutas
define('SEPARA_CARPETA', DIRECTORY_SEPARATOR);
define('DIR_ALMACEN', DIR_FRAMEWORK . 'almacen' . SEPARA_CARPETA);
define('DIR_PUBLICO', DIR_FRAMEWORK . 'publico' . SEPARA_CARPETA);
define('DIR_SPHP', DIR_FRAMEWORK . 'samariophp' . SEPARA_CARPETA);
define('DIR_SYS', DIR_SPHP . 'sistema' . SEPARA_CARPETA);

//RUTAS DEL FRAMEWORK
define('RUTA_AUTOLOAD', DIR_SPHP . 'autoload.php');
define('RUTA_INSTALADOR', DIR_SYS . 'instalador.php');
define('RUTA_ENRUTADOR', DIR_SYS . 'rutas/enrutador.php');


//CONFIGURACIONES
define('RUTA_CONFIGURACION', DIR_SYS . 'configuracion.php');
define('DIR_CONFIGURACIONES', DIR_SYS . 'configuracion' . SEPARA_CARPETA);
define('RUTA_CONFIG_BASEDEDATOS', DIR_CONFIGURACIONES . 'basededatos.php');
define('RUTA_CONFIG_VALIDACION', DIR_CONFIGURACIONES . 'validacion.php');
define('RUTA_CONFIG_ERRORES', DIR_CONFIGURACIONES . 'errores.php');
define('RUTA_CONFIG_LOGS', DIR_CONFIGURACIONES . 'logs.php');
define('RUTA_CONFIG_PHINX', DIR_CONFIGURACIONES . 'phinx.php');
define('RUTA_CONFIG_TWIG', DIR_CONFIGURACIONES . 'twig.php');
define('RUTA_CONFIG_SLIM', DIR_CONFIGURACIONES . 'slim.php');
define('RUTA_CONFIG_MEEDO', DIR_CONFIGURACIONES . 'medoo.php');

define('RUTA_FUNCIONES', DIR_SYS . 'utilidades/funciones.globales.php');

//MIDDLEWARE
define('RUTA_MANTENIMIENTO', DIR_SYS . 'middleware/mantenimiento.php');

//LOGS
define('RUTA_LOGS', DIR_ALMACEN . 'logs' . SEPARA_CARPETA);

//APLICACION
define('DIR_APP', DIR_SPHP . 'aplicacion' . SEPARA_CARPETA);
define('DIR_COMPONENTES', DIR_APP. 'componentes' . SEPARA_CARPETA);


//BASES DE DATOS
define('DIR_MODELOS', DIR_APP . 'modelos' . SEPARA_CARPETA);
define('DIR_BASE_DATOS', DIR_SPHP . 'basededatos' . SEPARA_CARPETA);
define('DIR_ESQUEMAS', DIR_BASE_DATOS . 'esquemas' . SEPARA_CARPETA);
define('RUTA_ESQUEMA_INICIAL', DIR_ESQUEMAS . 'esquema_inicial.php');
define('RUTA_ESQUEMA_AUDITORIA', DIR_ESQUEMAS . 'esquema_auditoria.php');
define('DIR_MIGRACIONES', DIR_BASE_DATOS . 'migraciones' . SEPARA_CARPETA);
define('RUTA_GENERAR_MIGRACIONES_MODELOS', DIR_BASE_DATOS . 'generador/GeneradorMigracionesModelos.php');

// Definir constantes para las vistas fijas o inicials
define('VISTA_EXTENSION', '.html.php');
define('DIR_VISTAS_PUBLICAS', DIR_PUBLICO . 'html' . SEPARA_CARPETA);
define('DIR_CORREOS', DIR_VISTAS_PUBLICAS . 'correos' . SEPARA_CARPETA);
define('DIR_PAGINASWEB', DIR_VISTAS_PUBLICAS . 'web' . SEPARA_CARPETA);
define('DIR_ERRORES', DIR_VISTAS_PUBLICAS . 'errores' . SEPARA_CARPETA);

//
// Definir rutas generales
//
//
define('RUTA_INICIO', '/inicio' );   // Ruta para la página de inicio
define('VISTA_INICIO', 'inicio');
//
//
define('RUTA_INSTALAR', '/instalacion');  // Ruta para la instalación
define('VISTA_INSTALACION', 'instalacion.preparacion' . VISTA_EXTENSION);
define('VISTA_INSTALACION_TERMINADA', 'instalacion/completada' . VISTA_EXTENSION);
//
//
// gestion de autenticacion
define('RUTA_USUARIO_REGISTRO', '/registro');
define('VISTA_USUARIO_REGISTRO', 'web/registro');
define('RUTA_USUARIO_RECUPERAR_CLAVE', '/recuperar-clave');
define('RUTA_USUARIO_VERIFICACION', '/verificar-correo');
//
define('RUTA_USUARIO_ENTRAR', '/inicio-sesion');
define('VISTA_USUARIO_ENTRAR', 'web/login');
//
define('RUTA_ADMIN', '/panel-principal');
define('VISTA_ADMIN', 'admin.plantilla');
//
define('RUTA_USUARIO_SALIR', '/cerrar-sesion');
//
define('RUTA_USUARIOS', '/usuarios');  // Ruta para los usuarios
//
//
define('RUTA_HELLO', '/hello/{name}'); // Ruta para el saludo
define('RUTA_TEST', '/test'); // Ruta para pruebas
//
// errores 
//
define('RUTA_404', '/404');   // Ruta para la página de inicio
define('VISTA_404', 'errores/404' . VISTA_EXTENSION);
