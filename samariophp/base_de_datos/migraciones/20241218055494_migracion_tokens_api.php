<?php

use Phinx\Migration\AbstractMigration;

final class MigracionTokensApi extends AbstractMigration {
    public function change() : void {
        $table = $this->table('tokens_api');
        $table->addColumn('usuario_id', 'integer');
        $table->addColumn('token', 'string', ['limit' => 120]);
        $table->addColumn('ultima_actividad', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('creado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_creacion', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('modificado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_modificacion', 'timestamp', ['default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
        $table->addColumn('eliminado_por', 'integer', ['null' => true]);
        $table->addColumn('fecha_eliminacion', 'timestamp', ['null' => true]);
        $table->addIndex(['token'], ['unique' => true]);
        $table->create();
    }

}
