<?php

return [
    'database_type' => config('base_de_datos.tipo'),
    'database_name' => config('base_de_datos.nombre_basedatos'),
    'server' => config('base_de_datos.servidor'),
    'username' => config('base_de_datos.nombre_usuario'),
    'password' => config('base_de_datos.clave_usuario'),
    'charset' => config('base_de_datos.conjunto_caracteres'),
    'port' => config('base_de_datos.puerto'),
    'fetch' => PDO::FETCH_OBJ, // Configuramos para que devuelva objetos,
    'option' => [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ // ← Aquí configuramos para devolver objetos
    ]
];
