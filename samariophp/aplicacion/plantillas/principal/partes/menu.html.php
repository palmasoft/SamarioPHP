<aside class="sidebar">
    <ul class="sidebar__menu-list">
        {% for item in menu %}
        <li class="sidebar__menu-item">
            <a href="{{ item.ruta }}" class="sidebar__menu-link">
                {% if item.icono %}
                <i class="fas fa-{{ item.icono }}"></i>
                {% endif %}
                {{ item.titulo }}
            </a>
            {% if item.submenu %}
            <ul class="sidebar__submenu-list">
                {% for subitem in item.submenu %}
                <li class="sidebar__submenu-item">
                    <a href="{{ subitem.ruta }}" class="sidebar__submenu-link">
                        {{ subitem.titulo }}
                    </a>
                </li>
                {% endfor %}
            </ul>
            {% endif %}
        </li>
        {% endfor %}
    </ul>
</aside>