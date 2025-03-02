<?php
// Rutas para el componente de autenticación
use SamarioPHP\Aplicacion\Controladores\AutenticacionControlador;
use SamarioPHP\Aplicacion\Controladores\RegistrarUsuarioControlador;
use SamarioPHP\Aplicacion\Controladores\RecuperarCuentaControlador;

return [
    "registro" => [RegistrarUsuarioControlador::class, 'mostrarVistaRegistro'],
    "registro_post" => [RegistrarUsuarioControlador::class, 'procesarRegistro'],
    "verificar" => [AutenticacionControlador::class, 'verificarCorreoElectronico'],
    "inicio-sesion" => [AutenticacionControlador::class, 'mostrarFormularioLogin'],
    "inicio-sesion_post" => [AutenticacionControlador::class, 'procesarLogin'],
    "recuperar-clave" => [RecuperarCuentaControlador::class, 'mostrarFormularioRecuperarClave'],
];
