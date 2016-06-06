{{ content() }}

<div class="text-left">
    {{ link_to("perfiles/create", "<i class='fa fa-plus-square'></i> Crear perfil", "class": "btn btn-primary") }}
</div>

<form method="post" action="{{ url("perfiles/search") }}" autocomplete="off" class="form-horizontal form-label-left">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Buscar perfiles</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Id</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ form.render("id",['class':"form-control col-md-7 col-xs-12"]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Nombre</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{ form.render("nombre",["class":"form-control col-md-7 col-xs-12"]) }}
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            {{ submit_button("Buscar", "class": "btn btn-primary") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>