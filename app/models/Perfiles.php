<?php
/**
 * phalcon model --name tbl_perfiles --namespace Dozmaz365\Models --extends Model --excludefields aud_fecha,aud_usuario_bd
 */
namespace Dozmaz365\Models;

use Phalcon\Mvc\Model;

/**
 * Class Perfiles
 * Todos los niveles de perfiles en la aplicación, Usado en conjunción con listas ACL
 * @package Dozmaz365\Models
 */
class Perfiles extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $nombre;

    /**
     *
     * @var string
     */
    public $activo;

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
        $this->hasMany('id', __NAMESPACE__ . '\Usuarios', 'perfilesId', array(
            'alias' => 'usuarios',
            'foreignKey' => array(
                'message' => 'El perfil no puede ser borrado porque esta siendo usardo por un usuario'
            )
        ));

        $this->hasMany('id', __NAMESPACE__ . '\Permisos', 'perfilesId', array(
            'alias' => 'permisos'
        ));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'perfiles';
    }

}
