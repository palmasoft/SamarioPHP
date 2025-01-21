<?php
return function ($configuracion) {

  return [
  'database_type' => $configuracion['base_de_datos']['tipo'],
  'database_name' => $configuracion['base_de_datos']['nombre_basedatos'],
  'server' => $configuracion['base_de_datos']['servidor'],
  'username' => $configuracion['base_de_datos']['nombre_usuario'],
  'password' => $configuracion['base_de_datos']['clave_usuario'],
  'charset' => $configuracion['base_de_datos']['conjunto_caracteres'],
  'port' => $configuracion['base_de_datos']['puerto'],
  'fetch' => PDO::FETCH_OBJ, // Configuramos para que devuelva objetos
  ];

// Conexión a la base de datos con Medoo
//$baseDeDatos = new Medoo($configuracionMedoo);
//  return $baseDeDatos;
};
