{% extends 'plantilla.html.php' %}
{% block title %}Registro Completado{% endblock %}
{% block menu %}{% endblock %}
{% block encabezado %}{% endblock %}
{% block contenido %}   

    <div class="container">
        <div class="mensaje-exito">
            <h1>¡Registro Exitoso!</h1>
            <p>Hemos enviado un correo de verificación a <strong>{{ correo }}</strong>.</p>
            <p>Por favor, revisa tu bandeja de entrada (o la carpeta de spam) y sigue las instrucciones para verificar tu cuenta.</p>
            <a href="/reenviar-verificacion" class="btn">¿No recibiste el correo? Reenviar</a>
            <a href="/" class="btn">Volver al Inicio</a>
        </div>
    </div>

{% endblock %}