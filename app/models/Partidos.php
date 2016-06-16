<?php
namespace Dozmaz365\Models;

class Partidos extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $PARTIDOS_ID;

    /**
     *
     * @var integer
     */
    public $LOCAL;

    /**
     *
     * @var integer
     */
    public $VISITANTE;

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
    public $PENALES_LOCAL;

    /**
     *
     * @var integer
     */
    public $PENALES_VISITANTE;

    /**
     *
     * @var integer
     */
    public $FASES_ID;

    /**
     *
     * @var string
     */
    public $FECHA;

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
        $this->belongsTo('AUD_USUARIO', 'Dozmaz365\Models\Usuarios', 'ID_USUARIO', array('alias' => 'Usuarios'));
        $this->belongsTo('LOCAL', 'Dozmaz365\Models\Equipos', 'EQUIPOS_ID', array('alias' => 'Local'));
        $this->belongsTo('VISITANTE', 'Dozmaz365\Models\Equipos', 'EQUIPOS_ID', array('alias' => 'Visitante'));
        $this->belongsTo('FASES_ID', 'Dozmaz365\Models\Fases', 'FASES_ID', array('alias' => 'Fases'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'partidos';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Partidos[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Partidos
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Retorna todas las fechas de los partidos registrados
     * @return mixed
     */
    public function fechasPartidos()
    {
        $sep = " WHERE ";
        $this->db = $this->getDi()->getShared('db');
        $sql = "SELECT DISTINCT(DATE_FORMAT(FECHA,'%Y-%m-%d')) as FECHA_PARTIDO FROM " . $this->getSource() . " ORDER BY FECHA";
        $lstNombres = $this->db->fetchAll($sql, \Phalcon\Db::FETCH_OBJ);
        return $lstNombres;
    }

    /**
     * Actualiza los puntos obtenidos por cada usuario
     *
     * SELECT
     * GOLES_LOCAL,GOLES_VISITANTE,
     * IF(0 = GOLES_LOCAL AND 2 = GOLES_VISITANTE,5,0) AS resultado_exacto,
     * IF((0>2 AND GOLES_LOCAL>GOLES_VISITANTE) OR (0<2 AND GOLES_LOCAL<GOLES_VISITANTE),3,0) AS coincide_ganador,
     * IF(0=GOLES_LOCAL OR 2 = GOLES_VISITANTE, 1,0) AS coinciden_goles,
     * IF(0 = GOLES_LOCAL AND 2 = GOLES_VISITANTE,5,IF((0>2 AND GOLES_LOCAL>GOLES_VISITANTE) OR (0<2 AND GOLES_LOCAL<GOLES_VISITANTE),3,0)+IF(0=GOLES_LOCAL OR 2 = GOLES_VISITANTE, 1,0)) AS puntos_ganados
     * FROM pronosticos
     * WHERE PARTIDOS_ID = 1
     *
     * si se acierta al resultado de los penales se pueden obtener 2 puntos adicionales
     *
     * @param $PARTIDOS_ID
     * @return mixed
     */
    public function calcularPuntosObtenidos($PARTIDOS_ID, $GOLES_LOCAL, $GOLES_VISITANTE, $fasesNombre)
    {
        $this->db = $this->getDi()->getShared('db');
        $sql = "UPDATE pronosticos 
SET PUNTOS_USUARIO =
  IF('" . $fasesNombre . "' like '%penales%',IF(" . $GOLES_LOCAL . " <> 0 OR " . $GOLES_VISITANTE . " <> 0,(CASE WHEN " . $GOLES_LOCAL . " = GOLES_LOCAL THEN 1 ELSE 0 END)+ (CASE WHEN " . $GOLES_VISITANTE . " = GOLES_VISITANTE THEN 1 ELSE 0 END),0),
    IF(" . $GOLES_LOCAL . " = GOLES_LOCAL AND " . $GOLES_VISITANTE . " = GOLES_VISITANTE,5,IF((" . $GOLES_LOCAL . ">" . $GOLES_VISITANTE . " AND GOLES_LOCAL>GOLES_VISITANTE) OR (" . $GOLES_LOCAL . "<" . $GOLES_VISITANTE . " AND GOLES_LOCAL < GOLES_VISITANTE)  OR (" . $GOLES_LOCAL . "=" . $GOLES_VISITANTE . " AND GOLES_LOCAL=GOLES_VISITANTE),3,0)+IF(" . $GOLES_LOCAL . " = GOLES_LOCAL OR " . $GOLES_VISITANTE . " = GOLES_VISITANTE, 1,0))
  )
WHERE PARTIDOS_ID = " . $PARTIDOS_ID;
        $this->db->execute($sql);
        return true;
    }
}
