{% extends 'plantilla.correo.php' %}

{% block asunto %}¡Bienvenido a {{ nombre_proyecto }}!{% endblock %}

{% block mensaje %}
<div class="container">
    <div class="header">
        <h1>¡Hola, {{ nombre }}!</h1>
    </div>
    <div class="content">
        <p>Gracias por unirte a <strong>{{ nombre_proyecto }}</strong>. Nos alegra mucho tenerte con nosotros.</p>
        <p>Para empezar, te recomendamos visitar los siguientes recursos:</p>
        <ul>
            <li><a href="{{ url_base }}/inicio">Tu Panel de Control</a></li>
            <li><a href="{{ url_base }}/ayuda">Centro de Ayuda</a></li>
            <li><a href="{{ url_base }}/comunidad">Comunidad</a></li>
        </ul>
        <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
        <p><strong>¡Disfruta de tu experiencia en {{ nombre_proyecto }}!</strong></p>
        <p>Saludos,<br>El equipo de {{ nombre_proyecto }}</p>
    </div>
</div>
{% endblock %}

{% block piecera %}
<div class="footer">
    <p>&copy; {{ anio }} {{ nombre_proyecto }}. Todos los derechos reservados.</p>
    <p>¿Necesitas ayuda? <a href="mailto:{{ correo_contacto }}">Contáctanos</a></p>
    <p><a href="{{ url_base }}/terminos">Términos y Condiciones</a> | <a href="{{ url_base }}/privacidad">Política de Privacidad</a></p>
</div>
{% endblock %}
