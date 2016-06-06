<form method="post" autocomplete="off" class="form-horizontal form-label-left">
    <div class="text-left">
        {{ link_to("perfiles", "&larr;  Atrás", "class": "btn btn-big btn-default") }}
        {{ submit_button("Guardar", "class": "btn btn-success") }}
    </div>

    {{ content() }}

    <div class="center scaffold">

        <h2>Editar perfil</h2>

        <ul class="nav nav-tabs">
            <li class="active"><a href="#A" data-toggle="tab">B&aacute;sico</a></li>
            <li><a href="#B" data-toggle="tab">Usuarios</a></li>
        </ul>

        <div class="tabbable">
            <div class="tab-content">
                <div class="tab-pane active" id="A">
                    <p>
                        {{ form.render("id") }}

                    <div class="item form-group col-md-12">
                        <label for="nombre" class="control-label col-md-3 col-sm-3 col-xs-12"
                               for="last-name">Nombre</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ form.render("nombre",["class":"form-control col-md-7 col-xs-12","required":"required"]) }}
                        </div>
                    </div>
                    <div class="item form-group col-md-12">
                        <label for="activo" class="control-label col-md-3 col-sm-3 col-xs-12">Activo?</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ form.render("activo",["class":"form-control col-md-7 col-xs-12","required":"required"]) }}
                        </div>
                    </div>
                    </p>
                </div>

                <div class="tab-pane" id="B">
                    <p>
                    <table class="table table-bordered table-striped" align="center">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Bloqueado?</th>
                            <th>Suspendido?</th>
                            <th>Activo?</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for user in perfil.usuarios %}
                            <tr>
                                <td>{{ user.id }}</td>
                                <td>{{ user.nombre }}</td>
                                <td>{{ user.bloqueado == 'Y' ? 'Si' : 'No' }}</td>
                                <td>{{ user.suspendido == 'Y' ? 'Si' : 'No' }}</td>
                                <td>{{ user.activo == 'Y' ? 'Si' : 'No' }}</td>
                                <td width="12%">{{ link_to("usuarios/edit/" ~ user.id, '<i class="icon-pencil"></i> Edit', "class": "btn btn-default") }}</td>
                                <td width="12%">{{ link_to("usuarios/delete/" ~ user.id, '<i class="icon-remove"></i> Delete', "class": "btn btn-default") }}</td>
                            </tr>
                        {% else %}
                            <tr>
                                <td colspan="3" align="center">No hay usuarios asignados a este perfil</td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    </p>
                </div>

            </div>
        </div>
    </div>
</form>
