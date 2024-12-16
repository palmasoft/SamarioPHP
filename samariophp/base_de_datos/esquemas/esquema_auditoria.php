<?php
return $campos_sistema = [
    'creado_por' => [
        'tipo' => 'integer',
        'opciones' => ['null' => true],
    ],
    'fecha_creacion' => [
        'tipo' => 'timestamp',
        'opciones' => ['default' => 'CURRENT_TIMESTAMP'],
    ],
    'modificado_por' => [
        'tipo' => 'integer',
        'opciones' => ['null' => true],
    ],
    'fecha_modificacion' => [
        'tipo' => 'timestamp',
        'opciones' => ['default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'],
    ],
    'eliminado_por' => [
        'tipo' => 'integer',
        'opciones' => ['null' => true],
    ],
    'fecha_eliminacion' => [
        'tipo' => 'timestamp',
        'opciones' => ['null' => true],
    ],
];
