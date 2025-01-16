{% extends 'plantilla.correo.php' %}
{% block asunto %}Bienvenido a Nuestra Plataforma{% endblock %}
{% block mensaje %}
<div class="container">
    <div class="header">
        <h1>¡Bienvenido, {{ nombre }}!</h1>
    </div>
    <div class="content">
        <p>Estamos encantados de que te hayas unido a <strong>{{ nombre_proyecto }}</strong>.</p>
        <p>En nuestra plataforma, encontrarás todas las herramientas necesarias para aprovechar al máximo tus objetivos. Aquí tienes algunos recursos que podrían interesarte:</p>
        <ul>
            <li><a href="{{ url_base }}/soporte">Soporte Técnico</a> - Si necesitas ayuda, estamos aquí para ti.</li>
            <li><a href="{{ url_base }}/documentacion">Documentación</a> - Descubre cómo sacar el máximo provecho de nuestra aplicación.</li>
            <li><a href="{{ url_base }}/novedades">Novedades</a> - Mantente al día con las últimas actualizaciones.</li>
        </ul>
        <p>Además, como parte de nuestra comunidad, puedes:</p>
        <ul>
            <li>Conectar con otros usuarios.</li>
            <li>Acceder a contenido exclusivo.</li>
            <li>Participar en eventos y promociones especiales.</li>
        </ul>
        <p>Si tienes alguna pregunta o simplemente quieres saludar, no dudes en contactarnos. </p>
        <p>Estamos aquí para asegurarnos de que tengas una experiencia excepcional.</p>
        <p><strong>¡Te damos una calurosa bienvenida y esperamos verte en acción!</strong></p>
        <p>Saludos cordiales,<br>El equipo de {{ nombre_proyecto }}</p>
    </div>    
</div>
{% endblock %}
