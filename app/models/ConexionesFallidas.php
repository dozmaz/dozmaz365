<?php

namespace Dozmaz365\Models;

use Phalcon\Mvc\Model;

/**
 * Class ConexionesFallidas
 * Registra las conexiones fallidas que hicieron usuarios registrados o no registrados
 * @package Dozmaz365\Models
 */
class ConexionesFallidas extends Model
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
    public $direccionIp;

    /**
     *
     * @var integer
     */
    public $intento;

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
        return 'conexiones_fallidas';
    }
}
