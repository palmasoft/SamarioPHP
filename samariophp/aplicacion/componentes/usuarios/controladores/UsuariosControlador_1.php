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

  function index(Peticion $peticion, Respuesta $respuesta) {
// Lógica para listar usuarios
    $respuesta->getBody()->write('Lista de usuarios');
    return $respuesta;
  }

  function nuevo(Peticion $peticion, Respuesta $respuesta) {
// Lógica para crear un usuario
    $respuesta->getBody()->write('Crear un usuario');
    return $respuesta;
  }
  
  
  public function administrador() {
    $usuarios = Usuario::todos();
    $this->renderizar('usuario/index', ['usuarios' => $usuarios]);
  }
  
}