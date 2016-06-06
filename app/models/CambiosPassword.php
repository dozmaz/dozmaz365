<?php
/**
 * phalcon model --name tbl_cambios_password --namespace Dozmaz365\Models --extends Model --excludefields aud_fecha,aud_usuario_bd
 */
namespace Dozmaz365\Models;

use Phalcon\Mvc\Model;

/**
 * Class CambiosPassword
 * Registra cuando un usuario cambis su contraseña
 * @package Dozmaz365\Models
 */
class CambiosPassword extends Model
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
     *
     * @var integer
     */
    public $creadoEn;

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
        return 'cambios_password';
    }

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmation
        $this->creadoEn = time();
    }
}
