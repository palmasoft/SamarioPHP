<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


return function ($configuracion) {

// Configurar Twig para las plantillas y vistas
  $loader = new \Twig\Loader\FilesystemLoader([DIR_VISTAS_PUBLICAS, DIR_APP]);
  $plantillas = new \Twig\Environment($loader, [
      'cache' => false, // O la ruta donde quieras guardar el caché
  ]);
// Agregar la variable global 'config' con el valor de $GLOBALS['config']
  $plantillas->addGlobal('app', $configuracion['aplicacion']);

  // Función personalizada para generar alertas
  $plantillas->addFunction(new \Twig\TwigFunction('alerta_error', function ($mensaje) {
            if (empty($mensaje)) {
              return "";
            }
            return '<div class="alerta alerta-error"><strong>Error:</strong> ' . htmlspecialchars($mensaje) . '</div>';
          }, ['is_safe' => ['html']]));

  return $plantillas;
};
