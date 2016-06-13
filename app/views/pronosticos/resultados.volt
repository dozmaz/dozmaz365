{% for id,pronostico in pronosticosUsuarios %}
    {% if pronostico['PUNTOS']==-1 %}
        {% if !loop.first %}
        </tbody>
        </table>
        </div>
        {% endif %}
            <div class="col-md-5">
            <table class="table table-bordered table-striped" align="center">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Local</th>
                <th>Visitante</th>
                <th>Puntos</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-info"><strong>{{ pronostico['NOMBRE'] }}</strong></td>
                <td class="text-info text-center"><strong>{{ pronostico['LOCAL'] }}</strong></td>
                <td class="text-info text-center"><strong>{{ pronostico['VISITANTE'] }}</strong></td>
                <td></td>
            </tr>
        {% else %}
            <tr>
                {% if pronostico['USUARIO_ID'] == usuario_id %}
                    <td class="label-success"><strong>{{ pronostico['NOMBRE'] }}</strong></td>
                    <td class="text-center label-success"><strong>{{ pronostico['LOCAL'] }}</strong></td>
                    <td class="text-center label-success"><strong>{{ pronostico['VISITANTE'] }}</strong></td>
                    <td class="text-center label-success"><strong>{{ pronostico['PUNTOS'] }}</strong></td>
                {% else %}
                    <td>{{ pronostico['NOMBRE'] }}</td>
                    <td class="text-center">{{ pronostico['LOCAL'] }}</td>
                    <td class="text-center">{{ pronostico['VISITANTE'] }}</td>
                    <td class="text-center">{{ pronostico['PUNTOS'] }}</td>
                {% endif %}
            </tr>
        {% endif %}
        {% if loop.last %}
            </tbody>
            </table>
            </div>
        {% endif %}
    {% else %}
        No hay usuarios registrados
    {% endfor %}