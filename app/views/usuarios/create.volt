<form method="post" autocomplete="off" class="form-horizontal form-label-left" novalidate>
    <div class="text-left">
        {{ link_to("usuarios", "&larr;  Atrás", "class": "btn btn-big btn-default") }}
        {{ submit_button("Guardar", "class": "btn btn-success") }}
    </div>

    {{ content() }}

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Crear un usuario</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br>
                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Nombre</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ form.render("nombre",["class":"form-control col-md-7 col-xs-12","required":"required"]) }}
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">E-mail</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ form.render("email",["class":"form-control col-md-7 col-xs-12","required":"required"]) }}
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Perfil</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ form.render("perfilesId",["class":"form-control col-md-7 col-xs-12","required":"required"]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- validator -->
{{ javascript_include('js/validarFormulario.js') }}
<!-- /validator -->