<?php
namespace Dozmaz365\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;

class PerfilesForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
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

        $this->add(new Select('activo', array(
            'Y' => 'Si',
            'N' => 'No'
        )));
    }
}
