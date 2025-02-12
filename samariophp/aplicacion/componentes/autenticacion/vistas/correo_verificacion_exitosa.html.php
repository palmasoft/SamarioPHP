{% extends 'plantilla.correo.php' %}
{% block asunto %}Bienvenido a Nuestra Plataforma{% endblock %}
{% block mensaje %}
<div class="container">
    <div class="header">
        <h1>¡Bienvenido, {{ nombre_usuario }}!</h1>
    </div>
    <div class="content">
        <p>Hola <strong>{{ nombre_usuario }}</strong>,</p>
        <p>Estamos encantados de tenerte en nuestra plataforma. Aquí podrás disfrutar de todas las herramientas y recursos que hemos diseñado para ti.</p>
        <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos. Estamos aquí para asistirte en cada paso del camino.</p>
        <a href="{{ url_plataforma }}" class="btn">Explorar la Plataforma</a>
        <p>Gracias por unirte a nosotros. ¡Esperamos que disfrutes de la experiencia!</p>
    </div>        
</div>
{% endblock %}
