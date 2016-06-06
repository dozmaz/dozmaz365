<form method="post" autocomplete="off" action="{{ url("usuarios/changePassword") }}" class="form-horizontal form-label-left" novalidate>
    <div class="text-left">
        {{ submit_button("Cambiar Contraseña", "class": "btn btn-success") }}
    </div>

    {{ content() }}

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Cambio de contrase&ntilde;a</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Contrase&ntilde;a</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ form.render("password",["class":"form-control col-md-7 col-xs-12","required":"required"]) }}
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="confirmPassword" class="control-label col-md-3 col-sm-3 col-xs-12">Confirmar Contrase&ntilde;a</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ form.render("confirmPassword",["class":"form-control col-md-7 col-xs-12","required":"required", "data-validate-linked":"email"]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
{{ javascript_include('js/validarFormulario.js') }}