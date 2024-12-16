<?php

use Phinx\Migration\AbstractMigration;

final class MigracionRoles extends AbstractMigration {
    public function change() : void {
        $table = $this->table('roles');
        $table->addColumn('nombre', 'string', ['limit' => 100]);
        $table->addColumn('descripcion', 'string', ['limit' => 255, 'null' => true]);
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
