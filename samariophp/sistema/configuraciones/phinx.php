<?php
// Devolver la configuración de Phinx con los valores dinámicos
return [
    'paths' => [
        'migrations' => 'samariophp/base_de_datos/migraciones',
        'seeds' => 'samariophp/base_de_datos/semillas',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => $configuracion['base_de_datos']['tipo'],
            'host' => $configuracion['base_de_datos']['servidor'],
            'name' => $configuracion['base_de_datos']['nombre_basedatos'],
            'user' => $configuracion['base_de_datos']['nombre_usuario'],
            'pass' => $configuracion['base_de_datos']['clave_usuario'],
            'port' => $configuracion['base_de_datos']['puerto'],
            'charset' => $configuracion['base_de_datos']['conjunto_caracteres'],
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => '162.240.97.33',
            'name' =>  'c0g3num3r0_l4r4v3lAPP',
            'user' => 'c0g3num3r0_l4r4v3l_USER',
            'pass' =>  'CNCwJz!]aL!]',
            'port' => 3306,
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => $configuracion['base_de_datos']['tipo'],
            'host' => $configuracion['base_de_datos']['servidor'],
            'name' => $configuracion['base_de_datos']['nombre_basedatos'],
            'user' => $configuracion['base_de_datos']['nombre_usuario'],
            'pass' => $configuracion['base_de_datos']['clave_usuario'],
            'port' => $configuracion['base_de_datos']['puerto'],
            'charset' => $configuracion['base_de_datos']['conjunto_caracteres'],
        ]
    ],
    'version_order' => 'creation',
];
