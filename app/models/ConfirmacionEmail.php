<?php
namespace Dozmaz365\Models;

use Phalcon\Mvc\Model;

class ConfirmacionEmail extends Model
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
    public $confirmado;

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
        return 'confirmacion_email';
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
        $this->confirmado = 'N';
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
     * Send a confirmation e-mail to the user after create the account
     */
    public function afterCreate()
    {
        $this->getDI()
            ->getMail()
            ->send(array(
                $this->usuario->email => $this->usuario->nombre
            ), "Por favor confirmar su E-mail", 'confirmation', array(
                'confirmUrl' => '/confirm/' . $this->codigo . '/' . $this->usuario->email
            ));
    }
}
