{% extends 'plantilla.correo.php' %}
{% block asunto %}Error en Verificación{% endblock %}
{% block mensaje %}   
<div>
    <h1>Hola, {{ nombre }}</h1>
    <p>Detectamos un problema al verificar tu correo en <strong>{{ nombre_proyecto }}</strong>.</p>
    <p>Por favor, intenta nuevamente o contáctanos si el problema persiste.</p>
    <p>Soporte: <a href="{{ url_soporte }}">{{ url_soporte }}</a></p>
    <p>Gracias por confiar en nosotros.</p>
    <p>Saludos,<br>El equipo de {{ nombre_proyecto }}</p>
</div>
{% endblock %}