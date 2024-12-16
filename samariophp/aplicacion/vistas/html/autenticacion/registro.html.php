{% extends 'plantilla.html.php' %}
{% block title %}Inicio de Sesión{% endblock %}
{% block menu %}{% endblock %}
{% block encabezado %}{% endblock %}
{% block contenido %}   

    <div class="contenedor">
        <h1>Registro de Usuario</h1>
        <?php if (isset($error)): ?>
            <div class="alerta-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="formulario-registro">
            <div class="campo">
                <label for="nombre">Nombre completo:</label>
                <input type="text" name="nombre" id="nombre" required placeholder="Ingresa tu nombre">
            </div>
            <div class="campo">
                <label for="correo">Correo electrónico:</label>
                <input type="email" name="correo" id="correo" required placeholder="tucorreo@ejemplo.com">
            </div>
            <div class="campo">
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" required placeholder="Contraseña segura">
            </div>
            <div class="campo">
                <label for="recontrasena">Confirmar contraseña:</label>
                <input type="password" name="recontrasena" id="recontrasena" required placeholder="Repite tu contraseña">
            </div>
            <button type="submit" class="btn-registrar">Registrar</button>
        </form>
        <p class="texto-secundario">
            ¿Ya tienes una cuenta? <a href="/login">Inicia sesión</a>
        </p>
    </div>
{% endblock %}