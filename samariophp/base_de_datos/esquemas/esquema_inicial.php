<?php
// Esquema inicial del sistema completo
return [
    'usuarios' => [
        'campos' => [
            'nombre' => ['tipo' => 'string'],
            'correo' => ['tipo' => 'string'],
            'clave' => ['tipo' => 'string'],
            'correo_verificado' => ['tipo' => 'boolean', 'opciones' => ['default' => false]],
            'token_verificacion' => ['tipo' => 'string', 'opciones' => ['longitud' => 64, 'null' => true]],
            'token_recuperacion' => ['tipo' => 'string', 'opciones' => ['longitud' => 64, 'null' => true]],
            'habilitar_2fa' => ['tipo' => 'boolean', 'opciones' => ['default' => false]],
            'clave_2fa' => ['tipo' => 'string', 'opciones' => ['null' => true]],
        ],
        'modelo' => 'Usuario',
        'campos_unicos' => ["nombre", 'correo'],
        'relaciones' => [
            'perfil' => ['tipo' => 'tieneUn', 'modelo' => 'Perfil', 'columna' => 'usuario_id'],
            'roles' => ['tipo' => 'perteneceAMuchos', 'modelo' => 'Rol', 'tabla_intermedia' => 'usuarios_roles', 'columna_intermedia_local' => 'usuario_id', 'columna_intermedia_foreanea' => 'rol_id'],
        ],
    ],
    'perfiles' => [
        'campos' => [
            'usuario_id' => ['tipo' => 'integer'],
            'telefono' => ['tipo' => 'string', 'opciones' => ['longitud' => 20, 'null' => true]],
            'direccion' => ['tipo' => 'string', 'opciones' => ['longitud' => 255, 'null' => true]],
            'fecha_nacimiento' => ['tipo' => 'date', 'opciones' => ['null' => true]],
        ],
        'modelo' => 'Perfil',
        'relaciones' => [
            'usuario' => ['tipo' => 'perteneceA', 'modelo' => 'Usuario', 'columna' => 'usuario_id'],
        ],
    ],
    'roles' => [
        'campos' => [
            'nombre' => ['tipo' => 'string', 'opciones' => ['longitud' => 100]],
            'descripcion' => ['tipo' => 'string', 'opciones' => ['longitud' => 255, 'null' => true]],
        ],
        'modelo' => 'Rol',
        'campos_unicos' => ['nombre'],
        'relaciones' => [
            'usuarios' => ['tipo' => 'perteneceAMuchos', 'modelo' => 'Usuario', 'tabla_intermedia' => 'usuarios_roles', 'columna_intermedia_local' => 'rol_id', 'columna_intermedia_foreanea' => 'usuario_id'],
            'permisos' => ['tipo' => 'perteneceAMuchos', 'modelo' => 'Permiso', 'tabla_intermedia' => 'roles_permisos', 'columna_intermedia_local' => 'rol_id', 'columna_intermedia_foreanea' => 'permiso_id'],
        ],
    ],
    'usuarios_roles' => [
        'campos' => [
            'usuario_id' => ['tipo' => 'integer'],
            'rol_id' => ['tipo' => 'integer'],
        ],
        'model' => null,
    ],
    'permisos' => [
        'campos' => [
            'nombre' => ['tipo' => 'string'],
            'descripcion' => ['tipo' => 'string', 'opciones' => ['null' => true]],
            'controlador' => ['tipo' => 'string'],
            'operacion' => ['tipo' => 'string'],
            'ruta' => ['tipo' => 'string'],
            'metodo_http' => ['tipo' => 'string', 'opciones' => ['longitud' => 10]],
            'activo' => ['tipo' => 'boolean', 'opciones' => ['default' => true]],
        ],
        'modelo' => 'Permiso',
        'campos_unicos' => ['nombre'],
        'relaciones' => [
            'roles' => ['tipo' => 'perteneceAMuchos', 'modelo' => 'Rol', 'tabla_intermedia' => 'roles_permisos', 'columna_intermedia_local' => 'permiso_id', 'columna_intermedia_foreanea' => 'rol_id'],
        ],
    ],
    'roles_permisos' => [
        'campos' => [
            'rol_id' => ['tipo' => 'integer'],
            'permiso_id' => ['tipo' => 'integer'],
        ],
        'modelo' => null,
    ],
    'tokens_api' => [
        'campos' => [
            'usuario_id' => ['tipo' => 'integer'],
            'token' => ['tipo' => 'string', 'opciones' => ['longitud' => 120]],
            'ultima_actividad' => ['tipo' => 'datetime', 'opciones' => ['default' => 'CURRENT_TIMESTAMP']],
        ],
        'modelo' => 'TokenApi',
        'campos_unicos' => ['token'],
        'relaciones' => [
            'usuario' => ['tipo' => 'perteneceA', 'modelo' => 'Usuario', 'columna' => 'usuario_id'],
        ],
    ],
    'sesiones' => [
        'campos' => [
            'usuario_id' => ['tipo' => 'integer'],
            'ip' => ['tipo' => 'string', 'opciones' => ['longitud' => 45]],
            'token_sesion' => ['tipo' => 'string'],
            'fecha_expiracion' => ['tipo' => 'datetime'],
            'user_agent' => ['tipo' => 'string', 'opciones' => ['longitud' => 255]],
            'ultima_actividad' => ['tipo' => 'datetime', 'opciones' => ['default' => 'CURRENT_TIMESTAMP']],
        ],
        'modelo' => 'Sesion',
        'relaciones' => [
            'usuario' => ['tipo' => 'perteneceA', 'modelo' => 'Usuario', 'columna' => 'usuario_id'],
        ],
    ],
];
