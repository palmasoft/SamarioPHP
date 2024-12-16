<?php

class TokenApi extends Modelo {
    public function usuario() {
        return $this->perteneceA('Usuario', 'usuario_id');
    }

}
