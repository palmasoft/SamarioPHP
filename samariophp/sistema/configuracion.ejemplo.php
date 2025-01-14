<?php
return [
    'sistema' => [
        'mantenimiento' => true, // Cambiar a false para habilitar el sistema
    ],
    'base_de_datos' => [
        'tipo' => 'mysql',
        'servidor' => '127.0.0.1', // Servidor local
        'puerto' => 3306,
        'nombre_basedatos' => 'nombre_base_datos', // Cambiar al nombre de tu base de datos
        'nombre_usuario' => 'root', // Usuario típico para localhost
        'clave_usuario' => '', // Generalmente vacío en localhost
        'conjunto_caracteres' => 'utf8mb4', // Recomendado para soporte extendido de caracteres
    ],
    'aplicacion' => [
        'nombre' => 'Mi Aplicación Local',
        'alias' => 'AppLocal',
        'version' => '1.0',
        'dominio' => 'localhost',
        'url_base' => 'http://localhost/', // Cambiar si usas un subdirectorio
        'logo' => '/imagenes/logo.png', // Ruta relativa
        'entorno' => 'desarrollo', // Indica que estás en desarrollo
    ],
    "enviador_correos" => [
        'email_respondera' => 'no-reply@localhost',
        'email_from' => 'app@localhost',
        'email_method' => 'smtp',
        'smtp' => [
            'host' => 'localhost',
            'auth' => false, // Deshabilitar autenticación para pruebas locales
            'username' => '',
            'password' => '',
            'secure' => '',
            'port' => 25, // Puerto típico para servidores locales
        ],
        'debug' => 2, // Modo depuración para pruebas
    ],
    "autenticador" => [
    // Configuraciones básicas de autenticación pueden agregarse aquí
    ],
    'archivos' => [
    // Configuraciones de manejo de archivos si aplica
    ]
];
