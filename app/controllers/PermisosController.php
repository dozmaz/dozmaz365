<?php
namespace Dozmaz365\Controllers;

use Dozmaz365\Models\Perfiles;
use Dozmaz365\Models\Permisos;

/**
 * Class PermisosController
 * Definelos permisos para varios niveles de perfil
 * @package Dozmaz365\Controllers
 */
class PermisosController extends \Phalcon\Mvc\Controller
{
    public function initialize()
    {
        $this->view->setVar('fotoPerfil', $this->session->get('fotoPerfil'));
        $this->view->setVar('fotoPerfil2', $this->session->get('fotoPerfil2'));
        $this->view->setVar('title','Permisos');
    }
    /**
     * View the permissions for a profile level, and change them if we have a POST.
     */
    public function indexAction()
    {
        $this->view->setTemplateBefore('private');

        if ($this->request->isPost()) {

            // Validate the profile
            $profile = Perfiles::findFirstById($this->request->getPost('perfilId'));

            if ($profile) {

                if ($this->request->hasPost('permisos')) {

                    // Deletes the current permissions
                    $profile->getPermisos()->delete();

                    // Save the new permissions
                    foreach ($this->request->getPost('permisos') as $permission) {

                        $parts = explode('.', $permission);

                        $permission = new Permisos();
                        $permission->perfilesId = $profile->id;
                        $permission->recurso = $parts[0];
                        $permission->accion = $parts[1];

                        $permission->save();
                    }

                    $this->flash->success('Los permisos fueron actualizados con éxito');
                }

                // Rebuild the ACL with
                $this->acl->rebuild();

                // Pass the current permissions to the view
                $this->view->permisos = $this->acl->getPermisos($profile);
            }

            $this->view->perfil = $profile;
        }

        // Pass all the active profiles
        $this->view->perfiles = Perfiles::find('activo = "Y"');
    }
}

