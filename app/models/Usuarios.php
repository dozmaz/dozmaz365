<?php

namespace Dozmaz365\Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as Email;
use Phalcon\Validation\Validator\Uniqueness as Uniqueness;

/**
 * Class Usuarios
 * Todos los usuarios registrados en la aplicación
 * @package Dozmaz365\Models
 */
class Usuarios extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $puedeCambiarPassword;

    /**
     *
     * @var string
     */
    public $bloqueado;

    /**
     *
     * @var string
     */
    public $suspendido;

    /**
     *
     * @var string
     */
    public $activo;

    /**
     *
     * @var integer
     */
    public $aud_usuario;

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        if (empty($this->password)) {

            // Generate a plain temporary password
            $tempPassword = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(12)));

            // The user must change its password in first login
            $this->puedeCambiarPassword = 'Y';

            // Use this password as default
            $this->password = $this->getDI()
                ->getSecurity()
                ->hash($tempPassword);
        } else {
            // The user must not change its password in first login
            $this->puedeCambiarPassword = 'N';
        }

        // The account must be confirmed via e-mail
        $this->activo = 'N';

        // The account is not suspended by default
        $this->suspendido = 'N';

        // The account is not banned by default
        $this->bloqueado = 'N';
    }

    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
    public function afterSave()
    {
        if ($this->activo == 'N') {

            $emailConfirmation = new ConfirmacionEmail();

            $emailConfirmation->usuariosId = $this->id;

            if ($emailConfirmation->save()) {
                $this->getDI()
                    ->getFlash()
                    ->notice('Una confirmación por correo ha sido enviada a ' . $this->email);
            }
        }
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new Email(['required' => true])
        );

        $validator->add('email', new Uniqueness([
            "model" => $this,
            "message" => "El correo electrónico ya está registrado en el sistema"
        ]));

        return $this->validationHasFailed() != true;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('perfilesId', __NAMESPACE__ . '\Perfiles', 'id', array(
            'alias' => 'perfil',
            'reusable' => true
        ));

        $this->hasMany('id', __NAMESPACE__ . '\ConexionesExitosas', 'usuariosId', array(
            'alias' => 'conexionesExitosas',
            'foreignKey' => array(
                'message' => 'El usuario no puede ser eliminado porque el/ella tiene actividad in el sistema'
            )
        ));

        $this->hasMany('id', __NAMESPACE__ . '\CambiosPassword', 'usuariosId', array(
            'alias' => 'cambiosPassword',
            'foreignKey' => array(
                'message' => 'El usuario no puede ser borrado porque el/ella tiene actividad en el sistema'
            )
        ));

        $this->hasMany('id', __NAMESPACE__ . '\RestablecerPasswords', 'usuariosId', array(
            'alias' => 'restablecerPasswords',
            'foreignKey' => array(
                'message' => 'El usuario no puede ser borrado porque el/ella tiene actividad en el sistema'
            )
        ));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'usuarios';
    }
}
