{% extends "plantilla.html.php" %}

{% block title %}Error {% endblock %}

{% block encabezado %}
{% endblock %}
{% block contenido %}
<div class="error-page">
    <h1>Error {{ codigo_error }}</h1>
    <p class="error-message">{{ mensaje_error }}</p>
    <p>Parece que algo salió mal. Puedes regresar a la <a href="/">página de inicio</a> o intentar buscar lo que necesitas.</p>
</div>
{% endblock %}
