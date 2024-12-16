<?php

Use SamarioPHP\BaseDeDatos\Generador\GeneradorMigraciones;
Use SamarioPHP\BaseDeDatos\Generador\GeneradorModelos;

class GeneradorMigracionesModelos {
  public static function generarTodo($esquema) {
    $campos_sistema = require RUTA_ESQUEMA_AUDITORIA; // Campos estándar del sistema

    $resultado = ['migraciones' => [], 'modelos' => []];
    foreach ($esquema as $tabla => $estructura) {
      $campos = $estructura['campos'];
      $campos_combinados = array_merge($campos, $campos_sistema); // Combinar campos
      $estructura['campos'] = $campos_combinados;
      //
      $RutaMigracion = GeneradorMigraciones::generarMigracion($tabla, $estructura);
      array_push($resultado['migraciones'], $RutaMigracion);
      $RutaModelo = GeneradorModelos::generarModelo($tabla, $estructura);
      array_push($resultado['modelos'], $RutaMigracion);
    }
    return $resultado;
  }
}