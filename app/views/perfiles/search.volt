{{ content() }}

<div class="text-left">
    {{ link_to("perfiles/index", "&larr;  Atrás", "class": "btn btn-big btn-default") }}
    {{ link_to("perfiles/create", "<i class='fa fa-plus-square'></i> Crear perfil", "class": "btn btn-primary") }}
</div>

{% for profile in page.items %}
    {% if loop.first %}
        <table class="table table-bordered table-striped" align="center">
        <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Activo?</th>
        </tr>
        </thead>
    {% endif %}
    <tbody>
    <tr>
        <td>{{ profile.id }}</td>
        <td>{{ profile.nombre }}</td>
        <td>{{ profile.activo == 'Y' ? 'Si' : 'No' }}</td>
        <td width="12%">{{ link_to("perfiles/edit/" ~ profile.id, '<i class="icon-pencil"></i> Editar', "class": "btn btn-default") }}</td>
        <td width="12%">{{ link_to("perfiles/delete/" ~ profile.id, '<i class="icon-remove"></i> Eliminar', "class": "btn btn-default") }}</td>
    </tr>
    </tbody>
    {% if loop.last %}
        {% if page.total_pages >1 %}
        <tbody>
        <tr>
            <td colspan="10" align="right">
                <div class="btn-group" role="toolbar" aria-label="...">
                    {{ link_to("perfiles/search", '<i class="icon-fast-backward"></i> Primero', "class": "btn btn-default") }}
                    {{ link_to("perfiles/search?page=" ~ page.before, '<i class="icon-step-backward"></i> Anterior', "class": "btn btn-default") }}
                    {{ link_to("perfiles/search?page=" ~ page.next, '<i class="icon-step-forward"></i> Siguiente', "class": "btn btn-default") }}
                    {{ link_to("perfiles/search?page=" ~ page.last, '<i class="icon-fast-forward"></i> &Uacute;ltimo', "class": "btn btn-default") }}
                </div>
                <div class="btn-group" role="toolbar" aria-label="...">
                    <div class="btn btn-default"><span class="badge">{{ page.current }}/{{ page.total_pages }}</span>
                    </div>
                </div>
            </td>
        </tr>
        <tbody>
            {% endif %}
        </table>
    {% endif %}
{% else %}
    No perfiles are recorded
{% endfor %}
