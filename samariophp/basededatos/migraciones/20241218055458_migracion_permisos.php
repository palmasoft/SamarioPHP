<?php

use Phinx\Migration\AbstractMigration;

final class MigracionPermisos extends AbstractMigration {
    public function change() : void {
        $table = $this->table('permisos');
        $table->addColumn('nombre', 'string');
        $table->addColumn('descripcion', 'string', ['null' => true]);
        $table->addColumn('controlador', 'string');
        $table->addColumn('operacion', 'string');
        $table->addColumn('ruta', 'string');
        $table->addColumn('metodo_http', 'string', ['limit' => 10]);
        $table->addColumn('activo', 'boolean', ['default' => true]);
        $table->addColumn('creado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_creacion', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('modificado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_modificacion', 'timestamp', ['default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
        $table->addColumn('eliminado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_eliminacion', 'timestamp', ['null' => true]);
        $table->addIndex(['nombre'], ['unique' => true]);
        $table->create();
    }

}
