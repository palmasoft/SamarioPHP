<?php
return [
    'sistema' => [
        'mantenimiento' => false, // Cambiar a false para deshabilitar 
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
        'url_base' => 'https://app.cogeunnumero.com/',
        'logo' => '/imagenes/samarioPHP.png',
        'entorno' => 'desarrollo', // 'desarrollo' o 'produccion'
    ],
    "enviador_correos" => [
        'email_respondera' => 'no-reply@cogeunnumero.com',
        'email_from' => 'app@cogeunnumero.com',
        'email_method' => 'smtp',
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
