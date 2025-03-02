<?php
namespace SamarioPHP\Aplicacion\Controladores;

use SamarioPHP\Sistema\Auth;

class AutenticacionControlador extends Controlador {

    /**
     * Muestra el formulario de login
     */
    public function mostrarFormularioLogin() {
        return vista(VISTA_USUARIO_ENTRAR);
    }

    /**
     * Procesa el inicio de sesión
     */
    public function procesarLogin() {
        // Verifica si faltan los datos requeridos (correo y contraseña)
        if ($this->faltanDatos(['correo', 'contrasena'])) {
            return vista(VISTA_USUARIO_ENTRAR, ['error' => "Todos los campos son requeridos."]);
        }

        // Llamamos a la función interna para iniciar la sesión (validando las credenciales)
        $this->iniciarSesion($this->correo, $this->contrasena);
    }

    /**
     * Inicia sesión manualmente
     * Este método ahora se encarga de autenticar y guardar la sesión
     */
    public function iniciarSesion($correo, $contrasena) {
        // Validar las credenciales del usuario
        $respuestaLogin = Auth::validarCredenciales($correo, $contrasena);

        // Si las credenciales son incorrectas, se muestra el error
        if ($respuestaLogin->tipo === 'error') {
            return vista(VISTA_USUARIO_ENTRAR, ['error' => $respuestaLogin->mensaje]);
        }

        // Si las credenciales son válidas, iniciar la sesión
        Auth::iniciarSesion($respuestaLogin->datos);

        // Redirigir al área de administración (o la ruta deseada)
        redirigir(RUTA_ADMIN);
    }

    /**
     * Cierra la sesión del usuario
     */
    public function cerrarSesion() {
        Auth::cerrarSesion();
        // Redirigir al formulario de login después de cerrar sesión
        redirigir(RUTA_USUARIO_ENTRAR);
    }

}