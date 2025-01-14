{% extends 'plantilla.html.php' %}
{% block title %}Verificación Exitosa{% endblock %}
{% block menu %}{% endblock %}
{% block encabezado %}{% endblock %}
{% block contenido %}   
<div class="container">
    <h1>Error en la Verificación</h1>
    <p>{{mensaje}}</p>
    <a href="/soporte" class="btn">Contactar Soporte</a>
    <a href="/reenviar-verificacion" class="btn">Reenviar Correo de Verificación</a>
</div>
{% endblock %}