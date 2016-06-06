{{ content() }}
<br/>
<div class="container">
<div class="col-md-4 col-sm-3 col-xs-1"></div>
<div class="col-md-4 col-sm-6 col-xs-10 center-block">
    {{ form('class': 'form-search', 'novalidate':'novalidate', 'role':'login') }}
        <div class="panel panel-default">

            <div class="panel-heading text-center">
                <h2><i class="fa fa-line-chart" style="font-size: 26px;"></i> Pronosticos</h2>
            </div>
            <div class="panel-body">
                <div class="item form-group">
                    {{ form.render('email',["class":"form-control input-lg","required":"required"]) }}
                </div>
                <div class="item form-group">
                    {{ form.render('password',["class":"form-control input-lg","required":"required"]) }}
                </div>
                {#
                <div class="form-group text-center">
                    {{ form.render('remember',["class":""]) }}
                    {{ form.label('remember',["class":""]) }}
                </div>
                #}
                <div class="form-group">
                    {{ form.render('go',['class':'btn btn-lg btn-primary btn-block']) }}
                </div>
                {#
                <div class="form-group text-center">
                    {{ link_to('session/signup', '<i class="fa fa-check"></i> Crear una cuenta', 'class': 'to_register') }}
                    o {{ link_to("session/forgotPassword", "Perdi&oacute; su contrase&ntilde;a?", 'class':"reset_pass") }}
                </div>
                #}
            </div>
        </div>
    {{ form.render('csrf', ['value': security.getToken()]) }}
    {{ endForm() }}
</div>
<div class="col-md-4 col-sm-3 col-xs-1"></div>
</div>
{{ javascript_include('js/validarFormulario.js') }}
<style type="text/css">
    .container {
        padding-top: 60px;
    }

    .panel {
        color: #5d5d5d;
        background: #f2f2f2;
        padding: 16px;
        border-radius: 10px;
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
    }
    input[type="text"],input[type="password"],input[type="submit"]{
        border-radius: 5px;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
    }
</style>