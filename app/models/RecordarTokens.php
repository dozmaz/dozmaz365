<?php
// phalcon model --name tbl_recordar_tokens --namespace Dozmaz365\Models --extends Model --excludefields aud_fecha,aud_usuario_bd
namespace Dozmaz365\Models;

use Phalcon\Mvc\Model;

/**
 * Class RecordarTokens
 * ALMACENA LOS TOKECS RECUERDAME
 * @package Dozmaz365\Models
 */
class RecordarTokens extends Model
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
    public $token;

    /**
     *
     * @var string
     */
    public $agenteUsuario;

    /**
     *
     * @var string
     */
    public $creadoEn;

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
        // Timestamp the confirmation
        $this->creadoEn = time();
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('usuariosId', __NAMESPACE__ . '\Usuarios', 'id', array(
            'alias' => 'usuario'
        ));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'recordar_tokens';
    }
}
