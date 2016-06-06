<?php
namespace Dozmaz365\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Dozmaz365\Models\Perfiles;

class UsersForm extends Form
{

    public function initialize($entity = null, $options = null)
    {

        // In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Text('id');
        }

        $this->add($id);

        $name = new Text('nombre', array(
            'placeholder' => 'Nombre'
        ));

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'El nombre es requerido'
            ))
        ));

        $this->add($name);

        $email = new Text('email', array(
            'placeholder' => 'Email'
        ));

        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'El e-mail es requerido'
            )),
            new Email(array(
                'message' => 'El e-mail no es v&aacute;lido'
            ))
        ));

        $this->add($email);

        $this->add(new Select('perfilesId', Perfiles::find('activo = "Y"'), array(
            'using' => array(
                'id',
                'nombre'
            ),
            'useEmpty' => true,
            'emptyText' => '-- Seleccionar un perfil --',
            'emptyValue' => '0'
        )));

        $this->add(new Select('bloqueado', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));

        $this->add(new Select('suspendido', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));

        $this->add(new Select('activo', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));
    }
}
