<form method="post" autocomplete="off" class="form-horizontal form-label-left">
    <div class="text-left">
        {{ link_to("usuarios", "&larr;  Atrás", "class": "btn btn-big btn-default") }}
        {{ submit_button("Guardar", "class": "btn btn-success") }}
    </div>

    {{ content() }}

    <div class="center scaffold">
        <h2>Editar usuarios</h2>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#A" data-toggle="tab">B&aacute;sico</a></li>
            <li><a href="#B" data-toggle="tab">Conexiones exitosas</a></li>
            <li><a href="#C" data-toggle="tab">Cambios de nontrase&ntilde;as</a></li>
            <li><a href="#D" data-toggle="tab">Contrase&ntilde;as restablecidas</a></li>
        </ul>

        <div class="tabbable">
            <div class="tab-content">
                <div class="tab-pane active" id="A">
                    <p>
                        {{ form.render("id") }}

                    <div class="item form-group col-md-6">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12" for="nombre">Nombre</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            {{ form.render("nombre",["class":"form-control col-md-7 col-xs-12","required":"required"]) }}
                        </div>
                    </div>
                    <div class="item form-group col-md-6">
                        <label for="email" class="control-label col-md-3 col-sm-3 col-xs-12">E-mail</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            {{ form.render("email",["class":"form-control col-md-7 col-xs-12","required":"required"]) }}
                        </div>
                    </div>
                    <div class="item form-group col-md-6">
                        <label for="perfilesId" class="control-label col-md-3 col-sm-3 col-xs-12">Perfil</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            {{ form.render("perfilesId",["class":"form-control col-md-7 col-xs-12","required":"required"]) }}
                        </div>
                    </div>
                    <div class="item form-group col-md-6">
                        <label for="bloqueado" class="control-label col-md-3 col-sm-3 col-xs-12">Bloqueado?</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            {{ form.render("bloqueado",["class":"form-control col-md-7 col-xs-12","required":"required"]) }}
                        </div>
                    </div>
                    <div class="item form-group col-md-6">
                        <label for="suspendido" class="control-label col-md-3 col-sm-3 col-xs-12">Suspendido?</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            {{ form.render("suspendido",["class":"form-control col-md-7 col-xs-12","required":"required"]) }}
                        </div>
                    </div>
                    <div class="item form-group col-md-6">
                        <label for="activo" class="control-label col-md-3 col-sm-3 col-xs-12">Confirmado?</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
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
                            <th>Direcci&oacute;nIP</th>
                            <th>Agente de usuario</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for login in user.conexionesExitosas %}
                            <tr>
                                <td>{{ login.id }}</td>
                                <td>{{ login.direccionIp }}</td>
                                <td>{{ login.agenteUsuario }}</td>
                            </tr>
                        {% else %}
                            <tr>
                                <td colspan="3" align="center">El usuario no tiene conexiones exitosas</td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    </p>
                </div>

                <div class="tab-pane" id="C">
                    <p>
                    <table class="table table-bordered table-striped" align="center">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Direcci&oacute;n IP</th>
                            <th>Agente de usuario</th>
                            <th>Fecha</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for change in user.cambiosPassword %}
                            <tr>
                                <td>{{ change.id }}</td>
                                <td>{{ change.direccionIp }}</td>
                                <td>{{ change.agenteUsuario }}</td>
                                <td>{{ date("Y-m-d H:i:s", change.creadoEn) }}</td>
                            </tr>
                        {% else %}
                            <tr>
                                <td colspan="3" align="center">El usuario no ha cambiado su contrase&ntilde;a</td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    </p>
                </div>

                <div class="tab-pane" id="D">
                    <p>
                    <table class="table table-bordered table-striped" align="center">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Fecha</th>
                            <th>Restablecido?</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for reset in user.restablecerPasswords %}
                            <tr>
                                <th>{{ reset.id }}</th>
                                <th>{{ date("Y-m-d H:i:s", reset.creadoEn) }}
                                <th>{{ reset.reset == 'Y' ? 'Yes' : 'No' }}
                            </tr>
                        {% else %}
                            <tr>
                                <td colspan="3" align="center">El usuario no ha solicitado restablecer su contrase&ntilde;a</td>
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
{{ javascript_include('js/validarFormulario.js') }}