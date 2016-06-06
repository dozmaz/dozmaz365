<?php

namespace Dozmaz365\Models;

use Phalcon\Mvc\Model;

/**
 * Class RestablecerPasswords
 * Almacena el restablecimiento de codigos de contraseña y su evolución
 * @package Dozmaz365\Models
 */
class RestablecerPasswords extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $usuariosId;

    /**
     *
     * @var string
     */
    public $codigo;

    /**
     *
     * @var string
     */
    public $creadoEn;

    /**
     *
     * @var string
     */
    public $modificadoEn;

    /**
     *
     * @var string
     */
    public $reset;

    /**
     *
     * @var integer
     */
    public $aud_usuario;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('usuariosId', __NAMESPACE__.'\Usuarios', 'id', array('alias' => 'usuario'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'restablecer_passwords';
    }

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmation
        $this->creadoEn = time();

        // Generate a random confirmation code
        $this->codigo = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(24)));

        // Set status to non-confirmed
        $this->reset = 'N';
    }

    /**
     * Sets the timestamp before update the confirmation
     */
    public function beforeValidationOnUpdate()
    {
        // Timestamp the confirmation
        $this->modificadoEn = time();
    }

    /**
     * Send an e-mail to users allowing him/her to reset his/her password
     */
    public function afterCreate()
    {
        $this->getDI()
            ->getMail()
            ->send(array(
                $this->usuario->email => $this->usuario->name
            ), "Restablecer su contraseña", 'reset', array(
                'resetUrl' => '/reset-password/' . $this->codigo . '/' . $this->usuario->email
            ));
    }
}
