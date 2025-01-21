<?php

use Phinx\Migration\AbstractMigration;

final class MigracionUsuariosRoles extends AbstractMigration {
    public function change() : void {
        $table = $this->table('usuarios_roles');
        $table->addColumn('usuario_id', 'integer');
        $table->addColumn('rol_id', 'integer');
        $table->addColumn('creado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_creacion', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('modificado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_modificacion', 'timestamp', ['default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
        $table->addColumn('eliminado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_eliminacion', 'timestamp', ['null' => true]);
        $table->create();
    }

}
