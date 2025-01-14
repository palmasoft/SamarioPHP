<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


return function ($configuracion) {

// Configurar Twig para las plantillas y vistas
  $loader = new \Twig\Loader\FilesystemLoader([DIR_VISTAS, DIR_CORREOS]);
  $plantillas = new \Twig\Environment($loader, [
      'cache' => false, // O la ruta donde quieras guardar el caché
  ]);
// Agregar la variable global 'config' con el valor de $GLOBALS['config']
  $plantillas->addGlobal('app', $configuracion['aplicacion']);
  
  return $plantillas;
};
