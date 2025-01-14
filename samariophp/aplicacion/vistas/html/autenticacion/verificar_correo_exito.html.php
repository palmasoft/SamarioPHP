{% extends 'plantilla.html.php' %}
{% block title %}Verificación Exitosa{% endblock %}
{% block menu %}{% endblock %}
{% block encabezado %}{% endblock %}
{% block contenido %}   
<div class="container">
    <h1>¡Verificación Exitosa!</h1>
    <p>{{ mensaje }}</p>
    <a href="/inicio-sesion" class="btn">Iniciar Sesión</a>
</div>
{% endblock %}