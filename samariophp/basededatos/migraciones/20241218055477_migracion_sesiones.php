<?php

use Phinx\Migration\AbstractMigration;

final class MigracionSesiones extends AbstractMigration {
    public function change() : void {
        $table = $this->table('sesiones');
        $table->addColumn('usuario_id', 'integer');
        $table->addColumn('ip', 'string', ['limit' => 45]);
        $table->addColumn('token_sesion', 'string');
        $table->addColumn('fecha_expiracion', 'datetime');
        $table->addColumn('user_agent', 'string', ['limit' => 255]);
        $table->addColumn('ultima_actividad', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('creado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_creacion', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('modificado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_modificacion', 'timestamp', ['default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
        $table->addColumn('eliminado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_eliminacion', 'timestamp', ['null' => true]);
        $table->create();
    }

}
