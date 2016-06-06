<?php
namespace Dozmaz365\Controllers;
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Dozmaz365\Models\Campeonatos;


class CampeonatosController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->setTemplateBefore('private');
        $this->view->setVar('title', 'Campeonatos');
    }
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for campeonatos
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Dozmaz365\Models\Campeonatos', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "CAMPEONATO_ID";

        $campeonatos = Campeonatos::find($parameters);
        if (count($campeonatos) == 0) {
            $this->flash->notice("The Buscar did not find any campeonatos");

            $this->dispatcher->forward(array(
                "controller" => "campeonatos",
                "action" => "index"
            ));

            return;
        }

        $paginator = new Paginator(array(
            'data' => $campeonatos,
            'limit'=> 10,
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
     * Edits a campeonato
     *
     * @param string $CAMPEONATO_ID
     */
    public function editAction($CAMPEONATO_ID)
    {
        if (!$this->request->isPost()) {

            $campeonato = Campeonatos::findFirstByCAMPEONATO_ID($CAMPEONATO_ID);
            if (!$campeonato) {
                $this->flash->error("campeonato was not found");

                $this->dispatcher->forward(array(
                    'controller' => "campeonatos",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->CAMPEONATO_ID = $campeonato->CAMPEONATO_ID;

            $this->tag->setDefault("CAMPEONATO_ID", $campeonato->CAMPEONATO_ID);
            $this->tag->setDefault("NOMBRE", $campeonato->NOMBRE);
            $this->tag->setDefault("FECHA_INI", $campeonato->FECHA_INI);
            $this->tag->setDefault("FECHA_FIN", $campeonato->FECHA_FIN);
            $this->tag->setDefault("LOGO", $campeonato->LOGO);
            $this->tag->setDefault("AUD_USUARIO", $campeonato->AUD_USUARIO);
            $this->tag->setDefault("AUD_FECHA", $campeonato->AUD_FECHA);
            $this->tag->setDefault("AUD_ESTADO", $campeonato->AUD_ESTADO);
            
        }
    }

    /**
     * Creates a new campeonato
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "campeonatos",
                'action' => 'index'
            ));

            return;
        }

        $campeonato = new Campeonatos();
        $campeonato->NOMBRE = $this->request->getPost("NOMBRE");
        $campeonato->FECHA_INI = $this->request->getPost("FECHA_INI");
        $campeonato->FECHA_FIN = $this->request->getPost("FECHA_FIN");
        $campeonato->LOGO = $this->request->getPost("LOGO");
        $campeonato->AUD_USUARIO = $this->request->getPost("AUD_USUARIO");
        $campeonato->AUD_FECHA = $this->request->getPost("AUD_FECHA");
        $campeonato->AUD_ESTADO = $this->request->getPost("AUD_ESTADO");
        

        if (!$campeonato->save()) {
            foreach ($campeonato->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "campeonatos",
                'action' => 'new'
            ));

            return;
        }

        $this->flash->success("campeonato was created successfully");

        $this->dispatcher->forward(array(
            'controller' => "campeonatos",
            'action' => 'index'
        ));
    }

    /**
     * Saves a campeonato edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "campeonatos",
                'action' => 'index'
            ));

            return;
        }

        $CAMPEONATO_ID = $this->request->getPost("CAMPEONATO_ID");
        $campeonato = Campeonatos::findFirstByCAMPEONATO_ID($CAMPEONATO_ID);

        if (!$campeonato) {
            $this->flash->error("campeonato does not exist " . $CAMPEONATO_ID);

            $this->dispatcher->forward(array(
                'controller' => "campeonatos",
                'action' => 'index'
            ));

            return;
        }

        $campeonato->NOMBRE = $this->request->getPost("NOMBRE");
        $campeonato->FECHA_INI = $this->request->getPost("FECHA_INI");
        $campeonato->FECHA_FIN = $this->request->getPost("FECHA_FIN");
        $campeonato->LOGO = $this->request->getPost("LOGO");
        $campeonato->AUD_USUARIO = $this->request->getPost("AUD_USUARIO");
        $campeonato->AUD_FECHA = $this->request->getPost("AUD_FECHA");
        $campeonato->AUD_ESTADO = $this->request->getPost("AUD_ESTADO");
        

        if (!$campeonato->save()) {

            foreach ($campeonato->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "campeonatos",
                'action' => 'edit',
                'params' => array($campeonato->CAMPEONATO_ID)
            ));

            return;
        }

        $this->flash->success("campeonato was updated successfully");

        $this->dispatcher->forward(array(
            'controller' => "campeonatos",
            'action' => 'index'
        ));
    }

    /**
     * Deletes a campeonato
     *
     * @param string $CAMPEONATO_ID
     */
    public function deleteAction($CAMPEONATO_ID)
    {
        $campeonato = Campeonatos::findFirstByCAMPEONATO_ID($CAMPEONATO_ID);
        if (!$campeonato) {
            $this->flash->error("campeonato was not found");

            $this->dispatcher->forward(array(
                'controller' => "campeonatos",
                'action' => 'index'
            ));

            return;
        }

        if (!$campeonato->delete()) {

            foreach ($campeonato->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "campeonatos",
                'action' => 'search'
            ));

            return;
        }

        $this->flash->success("campeonato was deleted successfully");

        $this->dispatcher->forward(array(
            'controller' => "campeonatos",
            'action' => "index"
        ));
    }

}
