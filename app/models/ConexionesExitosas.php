<?php

namespace Dozmaz365\Models;

use Phalcon\Mvc\Model;

/**
 * Class ConexionesExitosas
 * Registra las conexiones exitosas que hizo un usuario
 * @package Dozmaz365\Models
 */
class ConexionesExitosas extends Model
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
     * @var string
     */
    public $agenteUsuario;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('usuariosId', __NAMESPACE__ . '\Usuarios', 'id', array('alias' => 'usuario'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'conexiones_exitosas';
    }
}