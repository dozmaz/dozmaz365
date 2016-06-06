<?php
namespace Dozmaz365\Models;

class Equipos extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $EQUIPOS_ID;

    /**
     *
     * @var string
     */
    public $NOMBRE;

    /**
     *
     * @var BLOB
     */
    public $LOGO;

    /**
     *
     * @var integer
     */
    public $AUD_USUARIO;

    /**
     *
     * @var integer
     */
    public $AUD_FECHA;

    /**
     *
     * @var integer
     */
    public $AUD_ESTADO;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('EQUIPOS_ID', 'Partidos', 'LOCAL', array('alias' => 'Partidos'));
        $this->hasMany('EQUIPOS_ID', 'Partidos', 'VISITANTE', array('alias' => 'Partidos'));
        $this->belongsTo('AUD_USUARIO', 'Usuarios', 'ID_USUARIO', array('alias' => 'Usuarios'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'equipos';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Equipos[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Equipos
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
