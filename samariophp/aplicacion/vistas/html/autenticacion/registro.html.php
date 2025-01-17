{% extends 'plantilla.html.php' %}
{% block title %}Inicio de Sesión{% endblock %}
{% block menu %}{% endblock %}
{% block encabezado %}{% endblock %}
{% block contenido %}   
<div class="" >
    <div class="login">
        <div class="container">


            <!-- Logo y nombre del framework -->
            <div class="logo-container">
                <h1>SamarioPHP</h1>
                <a href="{{app.url_base}}"><img src="{{app.logo}}" alt="Logo {{app.alias}}" class="logo" /></a>
            </div>

            <!-- Título e inicio de sesión -->
            <div class="form-container">

                <h2>Registro de Usuario</h2>
                {{ alerta_error( error) }}
                <form method="POST" class="formulario-registro">
                    <div class="division" >
                        <div class="campo">
                            <label for="nombre">Nombre completo:</label>
                            <input type="text" name="nombre" id="nombre" value="{{nombre}}"
                                   required placeholder="Ingresa tu nombre">
                        </div>
                        <div class="campo">
                            <label for="correo">Correo electrónico:</label>
                            <input type="email" name="correo" id="correo" value="{{ correo }}"
                                   required placeholder="tucorreo@ejemplo.com" autocomplete="username"  />
                        </div>
                        <div class="campo">
                            <label for="contrasena">Contraseña:</label>
                            <input type="password" name="contrasena" id="contrasena" 
                                   required placeholder="Contraseña segura"  autocomplete="new-password">
                        </div>
                        <div class="campo">
                            <label for="recontrasena">Confirmar contraseña:</label>
                            <input type="password" name="recontrasena" id="recontrasena" 
                                   required placeholder="Repite tu contraseña" autocomplete="new-password" >
                        </div>
                    </div>                
                    <button type="submit" class="btn-primary btn-registrar">Registrar</button>
                </form>
                <p class="texto-secundario">
                    ¿Ya tienes una cuenta? <a href="/inicio-sesion">Inicia sesión</a>
                </p>


            </div>

        </div>
    </div>
</div>
{% endblock %}