<?php
namespace Dozmaz365\Models;

class Fases extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $FASES_ID;

    /**
     *
     * @var string
     */
    public $NOMBRE;

    /**
     *
     * @var integer
     */
    public $CAMPEONATO_ID;

    /**
     *
     * @var integer
     */
    public $NUMERO;

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
        $this->hasMany('FASES_ID', 'Partidos', 'FASES_ID', array('alias' => 'Partidos'));
        $this->belongsTo('AUD_USUARIO', 'Usuarios', 'ID_USUARIO', array('alias' => 'Usuarios'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'fases';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Fases[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Fases
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
