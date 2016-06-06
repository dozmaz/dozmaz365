{{ content() }}

<div id="wrapper">
    <div id="login" class=" form">
        <section class="login_content">
            {{ form('class': 'form-Buscar form-horizontal') }}

            <div align="left">
                <h2>Resg&iacute;strate</h2>
            </div>

            <div class="form-group">
                {{ form.label('nombre', ['class': "col-sm-3 control-label"]) }}
                <div class="col-sm-9">
                    {{ form.render('nombre', ['class': "form-control"]) }}
                </div>
            </div>
            {{ form.messages('nombre') }}

            <div class="form-group">
                {{ form.label('email', ['class': "col-sm-3 control-label"]) }}
                <div class="col-sm-9">
                    {{ form.render('email', ['class': "form-control"]) }}
                </div>
            </div>
            {{ form.messages('email') }}

            <div class="form-group">
                {{ form.label('password', ['class': "col-sm-3 control-label"]) }}
                <div class="col-sm-9">
                    {{ form.render('password', ['class': "form-control"]) }}
                </div>
            </div>
            {{ form.messages('password') }}

            <div class="form-group">
                {{ form.label('confirmPassword', ['class': "col-sm-3 control-label"]) }}
                <div class="col-sm-9">
                    {{ form.render('confirmPassword', ['class': "form-control"]) }}
                </div>
            </div>
            {{ form.messages('confirmPassword') }}

            <div class="form-group">
                {{ form.render('terms') }} {{ form.label('terms') }}
            </div>
            {{ form.messages('terms') }}

            <div class="form-group">
                {{ form.render('Sign Up') }}
            </div>
            {{ form.render('csrf', ['value': security.getToken()]) }}
            {{ form.messages('csrf') }}
            {{ endForm() }}
        </section>
    </div>
</div>