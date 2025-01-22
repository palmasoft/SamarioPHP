<?php

use Phinx\Migration\AbstractMigration;

final class MigracionPerfiles extends AbstractMigration {
    public function change() : void {
        $table = $this->table('perfiles');
        $table->addColumn('usuario_id', 'integer');
        $table->addColumn('telefono', 'string', ['limit' => 20, 'null' => true]);
        $table->addColumn('direccion', 'string', ['limit' => 255, 'null' => true]);
        $table->addColumn('fecha_nacimiento', 'date', ['null' => true]);
        $table->addColumn('creado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_creacion', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('modificado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_modificacion', 'timestamp', ['default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
        $table->addColumn('eliminado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_eliminacion', 'timestamp', ['null' => true]);
        $table->create();
    }

}
