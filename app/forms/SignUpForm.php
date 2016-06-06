<?php
namespace Dozmaz365\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class SignUpForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        $name = new Text('nombre');

        $name->setLabel('Nombre');

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'El nombre es requerido'
            ))
        ));

        $this->add($name);

        // Email
        $email = new Text('email');

        $email->setLabel('Correo electr&oacute;nico');

        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'El correo electr&oacute;nico es requerido'
            )),
            new Email(array(
                'message' => 'El correo electr&oacute;nico no es v&aacute;lido'
            ))
        ));

        $this->add($email);

        // Password
        $password = new Password('password');

        $password->setLabel('Contrase&ntilde;a');

        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'La contrase&ntilde;a es requerida'
            )),
            new StringLength(array(
                'min' => 8,
                'messageMinimum' => 'La contrase&ntilde;a es muy corta. M&iacute;nimo 8 caracteres'
            )),
            new Confirmation(array(
                'message' => 'La contrase&ntilde;a no coincide con la confirmaci&oacute;n',
                'with' => 'confirmPassword'
            ))
        ));

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword');

        $confirmPassword->setLabel('Confirmar contrase&ntilde;a');

        $confirmPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'La confirmaci&oacute;n de la contrase&ntilde;a no es requerida'
            ))
        ));

        $this->add($confirmPassword);

        // Remember
        $terms = new Check('terms', array(
            'value' => 'yes'
        ));

        $terms->setLabel('Acepto los t&eacute;rminos y condiciones');

        $terms->addValidator(new Identical(array(
            'value' => 'yes',
            'message' => 'Los t&eacute;rminos y condiciones deben ser aceptadas'
        )));

        $this->add($terms);

        // CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getSessionToken(),
            'message' => 'Fall&oacute; la validaci&oacute;n CSRF'
        )));

        $this->add($csrf);

        // Sign Up
        $this->add(new Submit('Sign Up', array(
            'value' => 'Registrarse',
            'class' => 'btn btn-success'
        )));
    }

    /**
     * Prints messages for a specific element
     */
    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
