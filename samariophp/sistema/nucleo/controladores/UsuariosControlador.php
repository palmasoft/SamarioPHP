<?php

class UsuariosControlador extends Controlador {
  public function administrador() {
    $usuarios = Usuario::todos();
    $this->renderizar('usuario/index', ['usuarios' => $usuarios]);
  }
}