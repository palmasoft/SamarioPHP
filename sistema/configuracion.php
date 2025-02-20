<?php
return [
    'sistema' => [
        'mantenimiento' => false, // Cambiar a false para deshabilitar 
        'entorno' => 'desarrollo', // 'desarrollo' o 'produccion'
        'dias_maximo_logs' => 14,
    ],
    'base_de_datos' => [
        'tipo' => 'mysql',
        'servidor' => '162.240.97.33',
        'puerto' => 3306,
        'nombre_basedatos' => 'c0g3num3r0_l4r4v3lAPP',
        'nombre_usuario' => 'c0g3num3r0_l4r4v3l_USER',
        'clave_usuario' => 'CNCwJz!]aL!]',
        'conjunto_caracteres' => 'utf8',
    ],
    'aplicacion' => [
//        'nombre' => 'Coge Un Numero - Elige tu suerte!!! ',
        'nombre' => 'FrameWork SamarioPHP',
        'alias' => 'SamarioPHP',
        'version' => '24.01', // año paquete  
        'dominio' => 'cogeunnumero.com',
        'url_base' => 'https://app.cogeunnumero.com',
        'logo' => '/img/samarioPHP.png',
        'correo_contacto' => 'contacto@cogeunnumero.com',
        'correo_soporte' => 'soporte@cogeunnumero.com',
    ],
    "enviador_correos" => [
        'email_respondera' => 'no-reply@cogeunnumero.com',
        'email_enviadopor' => 'app@cogeunnumero.com',
        'email_charset' => 'UTF-8',
        'email_codificacion' => 'base64',
        'email_metodo' => 'smtp',
        'smtp' => [
            'host' => 'vps-1148456.puroingeniosamario.com',
            'auth' => true,
            'username' => 'app@cogeunnumero.com',
            'password' => 'KM]LI]X;JDwb',
            'secure' => 'tls',
            'port' => 587,
        ],
        'debug' => 0,
    ],
    "autenticador" => [
    ],
    'archivos' => [
    ]
];
