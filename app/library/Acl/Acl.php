<?php
/**
 * Created by PhpStorm.
 * User: gcutipa
 * Date: 06/05/2016
 * Time: 11:25
 */

namespace Dozmaz365\Acl;

use Phalcon\Mvc\User\Component;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\Resource as AclResource;
use Dozmaz365\Models\Perfiles;

class Acl extends Component
{
    /**
     * The ACL Object
     *
     * @var \Phalcon\Acl\Adapter\Memory
     */
    private $acl;

    /**
     * The file path of the ACL cache file from APP_DIR
     *
     * @var string
     */
    private $filePath = '/cache/acl/data.txt';

    /**
     * Define the resources that are considered "private". These controller => actions require authentication.
     *
     * @var array
     */
    private $privateResources = array(
        'usuarios' => array(
            'index',
            'search',
            'edit',
            'create',
            'delete',
            'cambioPassword'
        ),
        'perfiles' => array(
            'index',
            'search',
            'edit',
            'create',
            'delete'
        ),
        'permisos' => array(
            'index'
        )
    );

    /**
     * Human-readable descriptions of the actions used in {@see $privateResources}
     *
     * @var array
     */
    private $actionDescriptions = array(
        'index' => 'Access',
        'search' => 'Buscar',
        'create' => 'Crear',
        'edit' => 'Editar',
        'delete' => 'Borrar',
        'cambioPassword' => 'Cambio de contraseña'
    );

    /**
     * Checks if a controller is private or not
     *
     * @param string $controllerName
     * @return boolean
     */
    public function isPrivate($controllerName)
    {
        $controllerName = strtolower($controllerName);
        return isset($this->privateResources[$controllerName]);
    }

    /**
     * Checks if the current profile is allowed to access a resource
     *
     * @param string $profile
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isAllowed($profile, $controller, $action)
    {
        return $this->getAcl()->isAllowed($profile, $controller, $action);
    }

    /**
     * Returns the ACL list
     *
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function getAcl()
    {
        // Check if the ACL is already created
        if (is_object($this->acl)) {
            return $this->acl;
        }

        // Check if the ACL is in APC
        if (function_exists('apc_fetch')) {
            $acl = apc_fetch('dozmaz365-acl');
            if (is_object($acl)) {
                $this->acl = $acl;
                return $acl;
            }
        }

        // Check if the ACL is already generated
        if (!file_exists(APP_DIR . $this->filePath)) {
            $this->acl = $this->rebuild();
            return $this->acl;
        }

        // Get the ACL from the data file
        $data = file_get_contents(APP_DIR . $this->filePath);
        $this->acl = unserialize($data);

        // Store the ACL in APC
        if (function_exists('apc_store')) {
            apc_store('dozmaz365-acl', $this->acl);
        }

        return $this->acl;
    }

    /**
     * Returns the permissions assigned to a profile
     *
     * @param Perfiles $profile
     * @return array
     */
    public function getPermissions(Perfiles $perfil)
    {
        $permisos = array();
        foreach ($perfil->getPermisos() as $permiso) {
            $permisos[$permiso->recurso . '.' . $permiso->accion] = true;
        }
        return $permisos;
    }

    /**
     * Returns all the resources and their actions available in the application
     *
     * @return array
     */
    public function getResources()
    {
        return $this->privateResources;
    }

    /**
     * Returns the action description according to its simplified name
     *
     * @param string $action
     * @return string
     */
    public function getActionDescription($action)
    {
        if (isset($this->actionDescriptions[$action])) {
            return $this->actionDescriptions[$action];
        } else {
            return $action;
        }
    }

    /**
     * Rebuilds the access list into a file
     *
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function rebuild()
    {
        $acl = new AclMemory();

        $acl->setDefaultAction(\Phalcon\Acl::DENY);

        // Register roles
        $perfiles = Perfiles::find('activo = "Y"');

        foreach ($perfiles as $perfil) {
            $acl->addRole(new AclRole($perfil->name));
        }

        foreach ($this->privateResources as $resource => $actions) {
            $acl->addResource(new AclResource($resource), $actions);
        }

        // Grant access to private area to role Users
        foreach ($perfiles as $perfil) {

            // Grant permissions in "permissions" model
            foreach ($perfil->getPermisos() as $permiso) {
                $acl->allow($perfil->nombre, $permiso->recurso, $permiso->accion);
            }

            // Always grant these permissions
            $acl->allow($perfil->nombre, 'usuarios', 'cambioPassword');
        }

        if (touch(APP_DIR . $this->filePath) && is_writable(APP_DIR . $this->filePath)) {

            file_put_contents(APP_DIR . $this->filePath, serialize($acl));

            // Store the ACL in APC
            if (function_exists('apc_store')) {
                apc_store('dozmaz365-acl', $acl);
            }
        } else {
            $this->flash->error(
                'El usuario no tiene permisos para precar la lista ACL como ' . APP_DIR . $this->filePath
            );
        }

        return $acl;
    }
}