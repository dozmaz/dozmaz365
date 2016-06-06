<?php

namespace Dozmaz365\Models;

use Phalcon\Mvc\Model;

/**
 * Class Permisos
 * Almacena los permisos por perfil
 * @package Dozmaz365\Models
 */
class Permisos extends Model
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
    public $perfilesId;

    /**
     *
     * @var string
     */
    public $recurso;

    /**
     *
     * @var string
     */
    public $accion;

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
        $this->belongsTo('profilesId', __NAMESPACE__ . '\Perfiles', 'id', array(
            'alias' => 'perfil'
        ));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'permisos';
    }


}
