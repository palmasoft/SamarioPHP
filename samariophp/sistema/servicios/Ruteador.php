<?php
namespace SamarioPHP\Ruteador;

use SamarioPHP\Http\Peticion;

class Ruteador {

  protected $rutas = [];
  protected $rutaActual;

  public function get($uri, $accion = null) {
    return $this->agregarRuta(['GET', 'HEAD'], $uri, $accion);
  }

  public function post($uri, $accion = null) {
    return $this->agregarRuta(['POST'], $uri, $accion);
  }

  public function put($uri, $accion = null) {
    return $this->agregarRuta(['PUT'], $uri, $accion);
  }

  public function delete($uri, $accion = null) {
    return $this->agregarRuta(['DELETE'], $uri, $accion);
  }

  public function agregarRuta($metodos, $uri, $accion) {
    $ruta = new Ruta($metodos, $uri, $accion);
    $this->rutas[] = $ruta;
    return $ruta;
  }

  public function despachar(Peticion $peticion) {
    foreach ($this->rutas as $ruta) {
      if ($ruta->coincide($peticion)) {
        $this->rutaActual = $ruta;
        return $ruta->ejecutar($peticion);
      }
    }

    return '404 - No Encontrado';
  }

}