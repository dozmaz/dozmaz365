{% for id,puntuacion in puntuaciones %}
    {% if puntuacion["NOMBRE"] != "Guido Cutipa Yujra" %}
        <div class="widget_summary">
            {% if id == usuario_id %}
                <div class="w_left w_25 text-danger">
                    <span><strong>{{ puntuacion["NOMBRE"] }}</strong></span>
                </div>
            {% else %}
                <div class="w_left w_25">
                    <span>{{ puntuacion["NOMBRE"] }}</span>
                </div>
            {% endif %}
            <div class="w_center w_55">
                <div class="progress">
                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ puntuacion["PORCENTAJE"] }}"
                         aria-valuemin="0" aria-valuemax="100" style="width: {{ puntuacion["PORCENTAJE"] }}%;">
                        <span class="sr-only">{{ puntuacion["PORCENTAJE"] }}% Complete</span>
                    </div>
                </div>
            </div>
            <div class="w_right w_20{% if id == usuario_id %} text-danger{% endif %}">
                <span>{{ puntuacion["PUNTOS"] }}</span>
            </div>
            <div class="clearfix"></div>
        </div>
    {% endif %}
{% endfor %}