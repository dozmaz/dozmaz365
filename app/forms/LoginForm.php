<?php
namespace Dozmaz365\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;

class LoginForm extends Form
{

    public function initialize()
    {
        // Email
        $email = new Text('email', array(
            'placeholder' => 'Email',
            'class' => 'form-control'
        ));

        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'El correo electr&oacute;nico es requerido'
            ))
        ));

        $this->add($email);

        // Password
        $password = new Password('password', array(
            'placeholder' => 'Contraseña',
            'class' => 'form-control'
        ));

        $password->addValidator(new PresenceOf(array(
            'message' => 'La contraseña es requerida'
        )));

        $password->clear();

        $this->add($password);

        // Remember
        $remember = new Check('remember', array(
            'value' => 'yes'
        ));

        $remember->setLabel('Recuerdame');

        $this->add($remember);

        // CSRF
        $csrf = new Hidden('csrf');

        /*$csrf->addValidator(new Identical(array(
            'value' => $this->security->getSessionToken(),
            'message' => 'Fall&oacute; la validaci&oacute;n CSRF'
        )));*/

        $csrf->clear();

        $this->add($csrf);

        $this->add(new Submit('go', array(
            'value' => 'Iniciar sesión',
            'class' => 'btn btn-default submit'
        )));
    }
}
