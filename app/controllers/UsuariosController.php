<?php
namespace Dozmaz365\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Dozmaz365\Forms\ChangePasswordForm;
use Dozmaz365\Forms\UsersForm;
use Dozmaz365\Models\Usuarios;
use Dozmaz365\Models\CambiosPassword;

/**
 * Class UsuariosController
 * ABML para administrar usuarios
 * @package Dozmaz365\Controllers
 */
class UsuariosController extends \Phalcon\Mvc\Controller
{
    public function initialize()
    {
        $this->view->setVar('fotoPerfil', $this->session->get('fotoPerfil'));
        $this->view->setVar('fotoPerfil2', $this->session->get('fotoPerfil2'));
        $this->view->setTemplateBefore('private');
        $this->view->setVar('title','Usuarios');
    }

    public function indexAction()
    {
        $this->persistent->conditions = null;
        $this->view->form = new UsersForm();
    }

    /**
     * Searches for users
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Dozmaz365\Models\Usuarios', $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $users = Usuarios::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice("La b&uacute;squeda no encontr&oacute; ning&uacute;n usuario");
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $users,
            "limit" => 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Creates a User
     */
    public function createAction()
    {
        if ($this->request->isPost()) {

            $usuario = new Usuarios();

            $identity = $this->auth->getIdentity();
            $aud_usuario = 0;
            if (isset($identity['id'])) {
                $aud_usuario = $identity['id'];
            }

            $usuario->assign(array(
                'nombre' => $this->request->getPost('nombre', 'striptags'),
                'perfilesId' => $this->request->getPost('perfilesId', 'int'),
                'email' => $this->request->getPost('email', 'email'),
                'aud_usuario' => $aud_usuario,
                'aud_fecha' => new \Phalcon\Db\RawValue('now()')
            ));

            if (!$usuario->save()) {
                $this->flash->error($usuario->getMessages());
            } else {

                $this->flash->success("El usuario fue creado satisfactoriamente");

                Tag::resetInput();
            }
        }

        $this->view->form = new UsersForm(null);
    }

    /**
     * Saves the user from the 'edit' action
     */
    public function editAction($id)
    {
        $usuario = Usuarios::findFirstById($id);
        if (!$usuario) {
            $this->flash->error("User was not found");
            return $this->dispatcher->forward(array(
                'action' => 'index'
            ));
        }

        if ($this->request->isPost()) {

            $identity = $this->auth->getIdentity();
            $aud_usuario = 0;
            if (isset($identity['id'])) {
                $aud_usuario = $identity['id'];
            }

            $usuario->assign(array(
                'nombre' => $this->request->getPost('nombre', 'striptags'),
                'perfilesId' => $this->request->getPost('perfilesId', 'int'),
                'password' => '$2y$08$Dffio8hjgRdHM4mYKIEKseK73uDu0z7HoG.VriNDOhCNBJnEkBPii',
                'email' => $this->request->getPost('email', 'email'),
                'bloqueado' => $this->request->getPost('bloqueado'),
                'suspendido' => $this->request->getPost('suspendido'),
                'activo' => $this->request->getPost('activo'),
                'aud_usuario' => $aud_usuario
            ));

            if (!$usuario->save()) {
                $this->flash->error($usuario->getMessages());
            } else {

                $this->flash->success("El usuario fue actualizado satisfactoriamente");

                Tag::resetInput();
            }
        }

        $this->view->user = $usuario;

        $this->view->form = new UsersForm($usuario, array(
            'edit' => true
        ));
    }

    /**
     * Deletes a User
     *
     * @param int $id
     */
    public function deleteAction($id)
    {
        $usuario = Usuarios::findFirstById($id);
        if (!$usuario) {
            $this->flash->error("El usuario no fue encontrado");
            return $this->dispatcher->forward(array(
                'action' => 'index'
            ));
        }

        if (!$usuario->delete()) {
            $this->flash->error($usuario->getMessages());
        } else {
            $this->flash->success("El usuario fue eliminado");
        }

        return $this->dispatcher->forward(array(
            'action' => 'index'
        ));
    }

    /**
     * Usuarios must use this action to change its password
     */
    public function changePasswordAction()
    {
        $form = new ChangePasswordForm();

        if ($this->request->isPost()) {

            if (!$form->isValid($this->request->getPost())) {

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $usuario = $this->auth->getUser();

                $usuario->password = $this->security->hash($this->request->getPost('password'));
                $usuario->mustChangePassword = 'N';

                $passwordChange = new CambiosPassword();
                $passwordChange->usuario = $usuario;
                $passwordChange->direccionIp = $this->request->getClientAddress();
                $passwordChange->agenteUsuario = $this->request->getUserAgent();

                if (!$passwordChange->save()) {
                    $this->flash->error($passwordChange->getMessages());
                } else {

                    $this->flash->success('Su contraseña fue cambiada satisfactoriamente');

                    Tag::resetInput();
                }
            }
        }

        $this->view->form = $form;
    }
}

