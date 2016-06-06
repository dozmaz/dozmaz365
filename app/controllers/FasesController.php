<?php
namespace Dozmaz365\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Dozmaz365\Models\Fases;


class FasesController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->setTemplateBefore('private');
        $this->view->setVar('title', 'Fases');
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for fases
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Dozmaz365\Models\Fases', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "FASES_ID";

        $fases = Fases::find($parameters);
        if (count($fases) == 0) {
            $this->flash->notice("The Buscar did not find any fases");

            $this->dispatcher->forward(array(
                "controller" => "fases",
                "action" => "index"
            ));

            return;
        }

        $paginator = new Paginator(array(
            'data' => $fases,
            'limit' => 10,
            'page' => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a fase
     *
     * @param string $FASES_ID
     */
    public function editAction($FASES_ID)
    {
        if (!$this->request->isPost()) {

            $fase = Fases::findFirstByFASES_ID($FASES_ID);
            if (!$fase) {
                $this->flash->error("fase was not found");

                $this->dispatcher->forward(array(
                    'controller' => "fases",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->FASES_ID = $fase->FASES_ID;

            $this->tag->setDefault("FASES_ID", $fase->FASES_ID);
            $this->tag->setDefault("NOMBRE", $fase->NOMBRE);
            $this->tag->setDefault("CAMPEONATO_ID", $fase->CAMPEONATO_ID);
            $this->tag->setDefault("NUMERO", $fase->NUMERO);
            $this->tag->setDefault("AUD_ESTADO", $fase->AUD_ESTADO);

        }
    }

    /**
     * Creates a new fase
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "fases",
                'action' => 'index'
            ));

            return;
        }

        $fase = new Fases();
        $fase->NOMBRE = $this->request->getPost("NOMBRE");
        $fase->CAMPEONATO_ID = $this->request->getPost("CAMPEONATO_ID");
        $fase->NUMERO = $this->request->getPost("NUMERO");
        $fase->AUD_ESTADO = $this->request->getPost("AUD_ESTADO");
        $fase->AUD_FECHA = new \Phalcon\Db\RawValue('now()');
        $auth = $this->auth->getIdentity();
        $fase->AUD_USUARIO = $auth['id'];

        if (!$fase->save()) {
            foreach ($fase->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "fases",
                'action' => 'new'
            ));

            return;
        }

        $this->flash->success("fase was created successfully");

        $this->dispatcher->forward(array(
            'controller' => "fases",
            'action' => 'index'
        ));
    }

    /**
     * Saves a fase edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "fases",
                'action' => 'index'
            ));

            return;
        }

        $FASES_ID = $this->request->getPost("FASES_ID");
        $fase = Fases::findFirstByFASES_ID($FASES_ID);

        if (!$fase) {
            $this->flash->error("fase does not exist " . $FASES_ID);

            $this->dispatcher->forward(array(
                'controller' => "fases",
                'action' => 'index'
            ));

            return;
        }

        $fase->NOMBRE = $this->request->getPost("NOMBRE");
        $fase->CAMPEONATO_ID = $this->request->getPost("CAMPEONATO_ID");
        $fase->NUMERO = $this->request->getPost("NUMERO");
        $fase->AUD_USUARIO = $this->request->getPost("AUD_USUARIO");
        $fase->AUD_FECHA = $this->request->getPost("AUD_FECHA");
        $fase->AUD_ESTADO = $this->request->getPost("AUD_ESTADO");


        if (!$fase->save()) {

            foreach ($fase->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "fases",
                'action' => 'edit',
                'params' => array($fase->FASES_ID)
            ));

            return;
        }

        $this->flash->success("fase was updated successfully");

        $this->dispatcher->forward(array(
            'controller' => "fases",
            'action' => 'index'
        ));
    }

    /**
     * Deletes a fase
     *
     * @param string $FASES_ID
     */
    public function deleteAction($FASES_ID)
    {
        $fase = Fases::findFirstByFASES_ID($FASES_ID);
        if (!$fase) {
            $this->flash->error("fase was not found");

            $this->dispatcher->forward(array(
                'controller' => "fases",
                'action' => 'index'
            ));

            return;
        }

        if (!$fase->delete()) {

            foreach ($fase->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "fases",
                'action' => 'search'
            ));

            return;
        }

        $this->flash->success("fase was deleted successfully");

        $this->dispatcher->forward(array(
            'controller' => "fases",
            'action' => "index"
        ));
    }

}
