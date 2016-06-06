/**
 * Created by gcutipa on 16/05/2016.
 */
$(function () {
// initialize the validator function
    validator.message.date = 'no es una fecha válida';
    validator.message.invalid = 'entrada inválida';
    validator.message.checked = 'se debe seleccionar';
    validator.message.empty = 'por favor ponga algo aquí';
    validator.message.min = 'es demasiado corto';
    validator.message.max = 'es demasiado largo';
    validator.message.number_min = 'demasiado bajo';
    validator.message.number_max = 'demasiado alto';
    validator.message.url = 'URL inválida';
    validator.message.number = 'no es un npumero';
    validator.message.email = 'el e-mail no es válido';
    validator.message.email_repeat = 'los e-mails no coinciden';
    validator.message.password_repeat = 'contraseñas no coinciden';
    validator.message.repeat = 'no coincide';
    validator.message.complete = 'input is not complete';
    validator.message.select = 'Por favor seleccione un opción';


// validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

    $('.multi.required').on('keyup blur', 'input', function () {
        validator.checkField.apply($(this).siblings().last()[0]);
    });

    $('form').submit(function (e) {
        e.preventDefault();
        var submit = true;

        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
            submit = false;
        }

        if (submit)
            this.submit();

        return false;
    });
});