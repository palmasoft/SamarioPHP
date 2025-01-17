{% extends 'plantilla.html.php' %}
{% block title %}Inicio de Sesión{% endblock %}
{% block js_inicio %}<script src="https://accounts.google.com/gsi/client" async defer></script>{% endblock %}
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
            <h2>Iniciar sesión</h2>
            {{ alerta_error(error) }}
            <form action="/inicio-sesion" method="POST" class="login-form">
                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" required placeholder="Ingrese su correo electrónico">
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" required 
                           autocomplete="on" placeholder="Ingrese su contraseña" >
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Iniciar sesión</button>
                </div>
            </form>

            <div class="google-login">
                <!-- Simulación de un botón de Google -->
                <button class="google-btn">
                    <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" alt="Iniciar sesión con Google">
                    Iniciar sesión con Google
                </button>
            </div>


            <div class="login-options">
                <p><a href="/recuperar-clave">¿Olvidaste tu contraseña?</a></p>
                <p><a href="/registro">¿No tienes cuenta? Regístrate aquí</a></p>
            </div>
        </div>
    </div>

</div>
{% endblock %}