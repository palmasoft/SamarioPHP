<?php

class Rol extends Modelo {
    public function usuarios() {
        return $this->perteneceAMuchos('Usuario', 'usuarios_roles', 'rol_id', 'usuario_id');
    }

    public function permisos() {
        return $this->perteneceAMuchos('Permiso', 'roles_permisos', 'rol_id', 'permiso_id');
    }

}
