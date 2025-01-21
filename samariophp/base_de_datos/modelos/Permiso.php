<?php

class Permiso extends Modelo {
    public function roles() {
        return $this->perteneceAMuchos('Rol', 'roles_permisos', 'permiso_id', 'rol_id');
    }

}
