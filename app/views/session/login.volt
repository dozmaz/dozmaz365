<section class="material-half-bg">
    <div class="cover"></div>
</section>
<section class="login-content">
    {{ content() }}
    <div class="logo">
        <h1><i class="fa fa-lg fa-fw fa-futbol-o"></i> Pron&oacute;sticos</h1>
    </div>
    <div class="login-box">
        {{ form('class': 'login-form', 'novalidate':'novalidate', 'role':'login') }}
        <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>INGRESAR</h3>
        <div class="form-group">
            <label class="control-label">USUARIO</label>
            {{ form.render('email',["class":"form-control input-lg","required":"required", "autofocus"]) }}
        </div>
        <div class="form-group">
            <label class="control-label">CONTRASE&Ntilde;A</label>
            {{ form.render('password',["class":"form-control input-lg","required":"required"]) }}
        </div>
        <div class="form-group">
            <div class="utility">
                <div class="animated-checkbox">
                    <label>
                        <input type="checkbox"><span class="label-text">Recordarme</span>
                    </label>
                </div>
                <p class="semibold-text mb-2"><a href="#" data-toggle="flip">Olvid&oacute; su contrase&ntilde;a?</a></p>
            </div>
        </div>
        <div class="form-group btn-container">
            {{ form.render('go',['class':'btn btn-lg btn-primary btn-block']) }}
        </div>
        {{ form.render('csrf', ['value': security.getToken()]) }}
        {{ endForm() }}
        <form class="forget-form" action="index.html">
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Olvid&oacute; su contrase&ntilde;a ?</h3>
            <div class="form-group">
                <label class="control-label">Contactese con el administrador del sitio</label>
            </div>
            <div class="form-group mt-3">
                <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Regresar al inicio</a></p>
            </div>
        </form>
    </div>
</section>
<!-- Essential javascripts for application to work-->

<!-- The javascript plugin to display page loading on top-->
{{ javascript_include('js/plugins/pace.min.js') }}
<script type="text/javascript">
    // Login Page Flipbox control
    $('.login-content [data-toggle="flip"]').click(function () {
        $('.login-box').toggleClass('flipped');
        return false;
    });
</script>