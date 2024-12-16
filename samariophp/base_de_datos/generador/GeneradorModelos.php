<?php
namespace SamarioPHP\BaseDeDatos\Generador;

use SamarioPHP\Ayudas\Utilidades;

class GeneradorModelos {
  //put your code here
  public static function generarModelo($tabla, $estructura) {
    // El nombre del modelo debe ser singular, por lo que se toma el nombre de la tabla en plural
    $nombreModelo = Utilidades::convertirNombreTablaAClase($tabla, true); // Convertir el nombre de la tabla a singular
    // Generamos el modelo en base al nombre en singular
    $modelo = "<?php\n\n";
    $modelo .= "class $nombreModelo extends Modelo {\n";

    // Si hay relaciones, las agregamos aquí
    if (isset($estructura['relaciones'])) {
      foreach ($estructura['relaciones'] as $relacion => $detalles) {
        if ($detalles['tipo'] === 'perteneceAMuchos') {
          $modelo .= "    public function $relacion() {\n";
          $modelo .= "        return \$this->perteneceAMuchos('" . $detalles['modelo'] . "', '" . $detalles['tabla_intermedia'] . "', '" . $detalles['columna_intermedia_local'] . "', '" . $detalles['columna_intermedia_foreanea'] . "');\n";
          $modelo .= "    }\n\n";
        } else {
          $modelo .= "    public function $relacion() {\n";
          $modelo .= "        return \$this->" . $detalles['tipo'] . "('" . $detalles['modelo'] . "', '" . $detalles['columna'] . "');\n";
          $modelo .= "    }\n\n";
        }
      }
    }
    $modelo .= "}\n";

    file_put_contents(DIR_MODELOS . "/" . $nombreModelo . ".php", $modelo);
  }
}