<?php
namespace Dozmaz365\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Dozmaz365\Forms\PerfilesForm;
use Dozmaz365\Models\Perfiles;

class PerfilesController extends \Phalcon\Mvc\Controller
{
    /**
     * Default action. Set the private (authenticated) layout (layouts/private.volt)
     */
    public function initialize()
    {
        $this->view->setVar('fotoPerfil', $this->session->get('fotoPerfil'));
        $this->view->setVar('fotoPerfil2', $this->session->get('fotoPerfil2'));
        $this->view->setTemplateBefore('private');
        $this->view->setVar('title', 'Perfiles');
    }

    /**
     * Default action, shows the Buscar form
     */
    public function indexAction()
    {
        $this->persistent->conditions = null;
        $this->view->form = new PerfilesForm();
    }

    /**
     * Searches for profiles
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Dozmaz365\Models\Perfiles', $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $profiles = Perfiles::find($parameters);
        if (count($profiles) == 0) {

            $this->flash->notice("La b&uacute;squeda no encontr&oacute; ning&uacute;n perfil");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $profiles,
            "limit" => 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Creates a new Profile
     */
    public function createAction()
    {
        if ($this->request->isPost()) {

            $perfil = new Perfiles();

            $identity = $this->auth->getIdentity();
            $aud_usuario = 0;
            if (isset($identity['id'])) {
                $aud_usuario = $identity['id'];
            }

            $perfil->assign(array(
                'nombre' => $this->request->getPost('nombre', 'striptags'),
                'activo' => $this->request->getPost('activo'),
                'aud_usuario' => $aud_usuario
            ));

            if (!$perfil->save()) {
                $this->flash->error($perfil->getMessages());
            } else {
                $this->flash->success("El perfil fue creado satisfactoriamente");
            }

            Tag::resetInput();
        }

        $this->view->form = new PerfilesForm(null);
    }

    /**
     * Edits an existing Profile
     *
     * @param int $id
     */
    public function editAction($id)
    {
        $perfil = Perfiles::findFirstById($id);
        if (!$perfil) {
            $this->flash->error("El perfil no fue encontrado");
            return $this->dispatcher->forward(array(
                'action' => 'index'
            ));
        }

        if ($this->request->isPost()) {

            $perfil->assign(array(
                'nombre' => $this->request->getPost('nombre', 'striptags'),
                'activo' => $this->request->getPost('activo')
            ));

            if (!$perfil->save()) {
                $this->flash->error($perfil->getMessages());
            } else {
                $this->flash->success("El perfil fue actualizado satisfactoriamente");
            }

            Tag::resetInput();
        }

        $this->view->form = new PerfilesForm($perfil, array(
            'edit' => true
        ));

        $this->view->perfil = $perfil;
    }

    /**
     * Deletes a Profile
     *
     * @param int $id
     */
    public function deleteAction($id)
    {
        $perfil = Profiles::findFirstById($id);
        if (!$perfil) {

            $this->flash->error("Profile was not found");

            return $this->dispatcher->forward(array(
                'action' => 'index'
            ));
        }

        if (!$perfil->delete()) {
            $this->flash->error($perfil->getMessages());
        } else {
            $this->flash->success("Profile was deleted");
        }

        return $this->dispatcher->forward(array(
            'action' => 'index'
        ));
    }
}

