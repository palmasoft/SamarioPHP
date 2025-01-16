{% extends 'plantilla.correo.php' %}
{% block asunto %}Bienvenido a Nuestra Plataforma{% endblock %}
{% block mensaje %}
<div class="container">
    <div class="header">
        <h1>Verifica tu correo</h1>
    </div>
    <div class="content">
        <p>Hola {{ nombre|e }},</p>
        <p>Gracias por registrarte en nuestra plataforma. Para completar el proceso, por favor verifica tu correo haciendo clic en el botón a continuación:</p>
        <a href="{{ enlace_verificacion }}" class="button">Verificar mi correo</a>
        <p>O copia y pega este enlace en tu navegador:</p>
        <p>{{ enlace_verificacion }}</p>
        <p>Si no reconoces esta acción, por favor ignora este mensaje.</p>
        <p>Saludos,<br>El equipo de {{ nombre_proyecto|e }}</p>
    </div>
    <div class="footer">
        <p>&copy; {{ anio }} {{ nombre_proyecto|e }}. Todos los derechos reservados.</p>
    </div>
</div>
{% endblock %}