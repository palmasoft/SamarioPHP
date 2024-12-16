<?php
namespace SamarioPHP\BaseDeDatos\Generador;
use SamarioPHP\Ayudas\Utilidades;
use SamarioPHP\Ayudas\Archivos;

/**
 * Clase para generar migraciones de base de datos.
 */
class GeneradorMigraciones {
  /**
   * Genera una migración para crear la tabla en la base de datos.
   * 
   * @param string $tabla Nombre de la tabla.
   * @param array $estructura Estructura de la tabla, incluyendo campos y relaciones.
   */
  public static function generarMigracion($tabla, $estructura) {

    // Inicia el archivo de migración
    $migracion = "<?php\n\n";
    $migracion .= "use Phinx\Migration\AbstractMigration;\n\n";
    $migracion .= "final class Migracion" . Utilidades::convertirNombreTablaAClase($tabla) . " extends AbstractMigration {\n";
    $migracion .= "    public function change() : void {\n";

    // Se crea la tabla principal
    $migracion .= "        \$table = \$this->table('$tabla');\n";

    // Agregar los campos
    foreach ($estructura['campos'] as $campo => $detalles) {
      $opciones = isset($detalles['opciones']) ? $detalles['opciones'] : [];
      $opcionesTexto = self::generarOpciones($opciones);
      $migracion .= "        \$table->addColumn('$campo', '{$detalles['tipo']}'$opcionesTexto);\n";
    }

    // Si hay campos únicos
    if (isset($estructura['campos_unicos'])) {
      $migracion .= "        \$table->addIndex(['" . implode("', '", $estructura['campos_unicos']) . "'], ['unique' => true]);\n";
    }

    // Agregar los campos de auditoría si están definidos
    if (isset($estructura['campos_auditoria'])) {
      foreach ($estructura['campos_auditoria'] as $campo => $detalles) {
        $opciones = isset($detalles['opciones']) ? $detalles['opciones'] : [];
        $opcionesTexto = self::generarOpciones($opciones);

        $migracion .= "        \$table->addColumn('$campo', '{$detalles['tipo']}'$opcionesTexto);\n";
      }
    }

    // Si hay relaciones, añadirlas
//    if (isset($estructura['relaciones'])) {
//      foreach ($estructura['relaciones'] as $relacion => $detalles) {
//        if (isset($detalles['tabla_intermedia'])) {
//          $tablaIntermedia = $detalles['tabla_intermedia'];
//          $migracion .= "        \$this->table('$tablaIntermedia')\n";
//          $migracion .= "            ->addColumn('" . $detalles['columna_intermedia_local'] . "', 'integer')\n";
//          $migracion .= "            ->addColumn('" . $detalles['columna_intermedia_foreanea'] . "', 'integer')\n";
//          $migracion .= "            ->create();\n";
//        }
//      }
//    }
    // Finaliza la creación de la tabla
    $migracion .= "        \$table->create();\n";

    // Finaliza la función `change`
    $migracion .= "    }\n\n";
    $migracion .= "}\n";

    // Guarda el archivo de migración con una marca de tiempo
    $archivo = self::guardarMigracion($tabla, $migracion);
    return $archivo;
  }

  private static function guardarMigracion($tabla, $migracion) {
    // Genera la versión de migración
    $version = Utilidades::generarIndicadorVersionPHINX(); 
// Verificar si ya existe un archivo con el mismo indicador de versión (antes de _migracion_)
    $contador = 1;
    while (true) {
      // Verificar si existe un archivo con el mismo indicador de versión
      $existeArchivo = false;

      // Escanea todos los archivos en la carpeta de migraciones
      $archivosMigracion = Archivos::buscarArchivosPorPalabra(DIR_MIGRACIONES, $version); 

      // Si hay archivos que comienzan con la misma versión, incrementar el contador
      if (!empty($archivosMigracion)) {
        $existeArchivo = true;
      } 

      // Si no existe un archivo con la misma versión, salir del bucle
      if (!$existeArchivo) {
        break;
      }

      // Si ya existe un archivo con el mismo indicador de versión, incrementar el contador y generar una nueva versión
      $version = intval(Utilidades::generarIndicadorVersionPHINX()) + $contador;
      $contador++;
    }
    $archivoBase = DIR_MIGRACIONES . "/" . $version . "_migracion_" . $tabla . ".php";
// Guarda el archivo de migración
    file_put_contents($archivoBase, $migracion);
    return $archivoBase;
  }

  // Función para generar las opciones de cada columna
  private static function generarOpciones($opciones) {
    $opcionesTexto = '';
    if (empty($opciones)) {
      return $opcionesTexto;
    }

    $opcionesTexto .= ', [';
    foreach ($opciones as $key => $value) {

      $key = self::traducirOpcion($key);

      if (is_bool($value)) {
        $opcionesTexto .= "'$key' => " . ($value ? 'true' : 'false') . ", ";
      } elseif (is_numeric($value)) {
        $opcionesTexto .= "'$key' => " . ( $value ) . ", ";
      } else {
        $opcionesTexto .= "'$key' => '$value', ";
      }
    }
    $opcionesTexto = rtrim($opcionesTexto, ', ') . ']';

    return $opcionesTexto;
  }

  static $opciones = ["longitud" => "limit", "unico" => "unique"];

  private static function traducirOpcion($nombre) {
    if (array_key_exists($nombre, self::$opciones)) {
      return self::$opciones[$nombre];
    }
    // Si el nombre no existe en el array, puedes devolver un valor por defecto o manejar el error
    return $nombre;
  }
}