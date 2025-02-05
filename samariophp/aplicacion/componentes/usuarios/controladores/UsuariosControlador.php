<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace SamarioPHP\Aplicacion\Controladores;

/**
 * Description of UsuariosControlador
 *
 * @author SISTEMAS
 */
class UsuariosControlador extends Controlador {

//put your code here

  function nuevo() {
// Lógica para crear un usuario
    $this->respuesta->getBody()->write('Crear un usuario');
    return $respuesta;
  }

  public function index() {
    $usuarios = Usuario::todos();
    $this->renderizar('usuario.tabla', ['usuarios' => $usuarios]);
    $this->respuesta->getBody()->write('Lista de usuarios');
    return $respuesta;
  }

}