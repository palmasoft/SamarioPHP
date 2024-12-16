<?php

class Sesion extends Modelo {
    public function usuario() {
        return $this->perteneceA('Usuario', 'usuario_id');
    }

}
