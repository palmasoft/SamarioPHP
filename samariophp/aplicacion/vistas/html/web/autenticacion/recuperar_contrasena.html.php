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
            <a href="{{app.url_base}}"><img src="{{app.logo}}" alt="Logo {{app.alias}}" class="logo" /></a>
        </div>

        <!-- Título e inicio de sesión -->
        <div class="form-container">
            
            <h2>Recuperación de Contraseña</h2>
            
            <form action="/recuperar-contrasena" method="POST">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" required placeholder="Ingrese su correo electrónico">
                
                <button type="submit" class="btn-primary">Enviar instrucciones</button>
            </form>
            <p><a href="/inicio-sesion">Volver al inicio de sesión</a></p>

        </div>
    </div>
</div>

{% endblock %} 