<?php

namespace Dozmaz365\Models;

class Pronosticos extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $PRONOSTICOS_ID;

    /**
     *
     * @var integer
     */
    public $GOLES_LOCAL;

    /**
     *
     * @var integer
     */
    public $GOLES_VISITANTE;

    /**
     *
     * @var integer
     */
    public $PUNTOS_USUARIO;

    /**
     *
     * @var integer
     */
    public $PARTIDOS_ID;

    /**
     *
     * @var integer
     */
    public $USUARIOS_ID;

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
        $this->belongsTo('AUD_USUARIO', 'Dozmaz365\Models\Usuarios', 'id', array('alias' => 'Usuarios'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'pronosticos';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Pronosticos[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Pronosticos
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function permitidoModificar($FECHA){
        $tz = 'America/La_Paz';
        $fechaActual = new \DateTime($this->fechaActual('Y-m-d H:i:00'), new \DateTimeZone ($tz));
        $fechaPartido = new \DateTime($FECHA, new \DateTimeZone ($tz));
        return ($fechaActual < $fechaPartido);
    }
    public function fechaActual($formato = 'Y-m-d H:i:s')
    {
        $tz = 'America/La_Paz';
        $timestamp = time();
        $dt = new \DateTime ("now", new \DateTimeZone ($tz));
        $dt->setTimestamp($timestamp);
        return $dt->format($formato);
    }
}