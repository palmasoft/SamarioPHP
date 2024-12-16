<?php

class Usuario extends Modelo {
    public function perfil() {
        return $this->tieneUn('Perfil', 'usuario_id');
    }

    public function roles() {
        return $this->perteneceAMuchos('Rol', 'usuarios_roles', 'usuario_id', 'rol_id');
    }

}
