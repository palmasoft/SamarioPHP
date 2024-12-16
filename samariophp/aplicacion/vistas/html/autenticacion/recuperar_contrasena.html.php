{% extends 'plantilla.html.php' %}
{% block title %}Recuperación de Contraseña{% endblock %}
{% block menu %}{% endblock %}
{% block encabezado %}{% endblock %}
{% block contenido %}   
<div class="login">
    <div class="container">
        <!-- Logo y nombre del framework -->
        <div class="logo-container">
            <h1>SamarioPHP</h1>
            <img src="{{config.aplicacion.logo}}" alt="Logo {{config.aplicacion.alias}}" class="logo" />
        </div>

        <!-- Título e inicio de sesión -->
        <div class="form-container">
            
            <h2>Recuperación de Contraseña</h2>
            
            <form action="/recuperar-contrasena" method="POST">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" required placeholder="Ingrese su correo electrónico">
                <button type="submit">Enviar instrucciones</button>
            </form>
            <p><a href="/login">Volver al inicio de sesión</a></p>

        </div>
    </div>
</div>

{% endblock %} 