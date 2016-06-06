{{ content() }}

<div class="text-left">
    {{ link_to("usuarios/index", "&larr;  Atrás", "class": "btn btn-big btn-default") }}
    {{ link_to("usuarios/create", "<i class='fa fa-plus-square'></i> Crear usuarios", "class": "btn btn-primary") }}
</div>

{% for user in page.items %}
    {% if loop.first %}
        <table class="table table-bordered table-striped" align="center">
        <thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Perfil</th>
            <th>Bloqueado?</th>
            <th>Suspendido?</th>
            <th>Confirmado?</th>
        </tr>
        </thead>
    {% endif %}
    <tbody>
    <tr>
        <td>{{ user.id }}</td>
        <td>{{ user.nombre }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.perfil.nombre }}</td>
        <td>{{ user.bloqueado == 'Y' ? 'Yes' : 'No' }}</td>
        <td>{{ user.suspendido == 'Y' ? 'Yes' : 'No' }}</td>
        <td>{{ user.activo == 'Y' ? 'Yes' : 'No' }}</td>
        <td width="12%">{{ link_to("usuarios/edit/" ~ user.id, '<i class="icon-pencil"></i> Editar', "class": "btn") }}</td>
        <td width="12%">{{ link_to("usuarios/delete/" ~ user.id, '<i class="icon-remove"></i> Eliminar', "class": "btn") }}</td>
    </tr>
    </tbody>
    {% if loop.last %}
        <tbody>
        <tr>
            <td colspan="10" align="right">
                <div class="btn-group" role="toolbar" aria-label="...">
                    {{ link_to("usuarios/search", '<i class="icon-fast-backward"></i> Primero', "class": "btn btn-default") }}
                    {{ link_to("usuarios/search?page=" ~ page.before, '<i class="icon-step-backward"></i> Anterior', "class": "btn btn-default") }}
                    {{ link_to("usuarios/search?page=" ~ page.next, '<i class="icon-step-forward"></i> Siguiente', "class": "btn btn-default") }}
                    {{ link_to("usuarios/search?page=" ~ page.last, '<i class="icon-fast-forward"></i> &Uacute;ltimo', "class": "btn btn-default") }}
                </div>
                <div class="btn-group" role="toolbar" aria-label="...">
                    <div class="btn btn-default"><span class="badge">{{ page.current }}/{{ page.total_pages }}</span>
                    </div>
                </div>
            </td>
        </tr>
        <tbody>
        </table>
    {% endif %}
{% else %}
    No hay usuarios registrados
{% endfor %}
