<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace App\Controladores;

/**
 * Description of InstaladorControlador
 *
 * @author SISTEMAS
 */
class InstaladorControlador extends Controlador {
  public function preparacion() {
    $logger->info('[INSTALACIÓN] Verificando tablas existentes antes de instalar...');

    try {
      $tablas = $baseDeDatos->query("SHOW TABLES")->fetchAll();

      if (count($tablas) > 0) {
        $mensaje = 'La base de datos ya contiene tablas. Elimine las tablas existentes si desea realizar una nueva instalación.';
        $mensaje_tipo = 'error';
        $logger->warning('[INSTALACIÓN] Tablas existentes detectadas.');
      } else {
        $mensaje = 'Bienvenido al instalador de SamarioPHP. Presione \"Iniciar instalación\" para continuar.';
        $mensaje_tipo = 'iniciar_instalacion';
      }

      $contenido = $plantillas->render(VISTA_INSTALACION, [
          'config' => $configuracion,
          'mensaje' => $mensaje,
          'mensaje_tipo' => $mensaje_tipo
      ]);

      $respuesta->getBody()->write($contenido);
      return $respuesta;
    } catch (Exception $e) {
      $logger->error('[INSTALACIÓN] Error al verificar las tablas: ' . $e->getMessage());
      throw $e;
    }
  }
}