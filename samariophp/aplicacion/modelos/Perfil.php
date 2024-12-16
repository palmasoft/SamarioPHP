<?php

class Perfil extends Modelo {
    public function usuario() {
        return $this->perteneceA('Usuario', 'usuario_id');
    }

}
