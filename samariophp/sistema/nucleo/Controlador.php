<?php

class Controlador {
  //put your code here
  // Métodos comunes como renderizar vistas, redirección, etc.
  public function renderizar($vista, $datos = []) {
    include "app/vistas/{$vista}.php";
  }

  public function redirigir($url) {
    header("Location: {$url}");
    exit;
  }
}