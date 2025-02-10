<?php

return [
    'default' => 'aplicacion',  // Canal predeterminado
    'canales' => [
        'aplicacion' => [
            'driver' => 'archivo',
            'ruta' => RUTA_LOGS . '/aplicacion.log',
            'nivel' => 'error',
        ],
        'servidor' => [
            'driver' => 'archivo',
            'ruta' => RUTA_LOGS . '/servidor.log',
            'nivel' => 'critical',
        ],
        'eventos' => [
            'driver' => 'archivo',
            'ruta' => RUTA_LOGS . '/eventos.log',
            'nivel' => 'info',
        ],
        'basededatos' => [
            'driver' => 'archivo',
            'ruta' => RUTA_LOGS . '/basededatos.log',
            'nivel' => 'info',
        ],
        'autenticacion' => [
            'driver' => 'archivo',
            'ruta' => RUTA_LOGS . '/autenticacion.log',
            'nivel' => 'info',
        ],
    ],
];
