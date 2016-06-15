<?php
namespace Dozmaz365\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Dozmaz365\Models\Partidos;
use Dozmaz365\Models\Usuarios;
use Dozmaz365\Models\Pronosticos;

class PartidosController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->setTemplateBefore('private');
        $this->view->setVar('title', 'Equipos');
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for partidos
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Dozmaz365\Models\Partidos', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "PARTIDOS_ID";

        $partidos = Partidos::find($parameters);
        if (count($partidos) == 0) {
            $this->flash->notice("The Buscar did not find any partidos");

            $this->dispatcher->forward(array(
                "controller" => "partidos",
                "action" => "index"
            ));

            return;
        }

        $paginator = new Paginator(array(
            'data' => $partidos,
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
        $this->tag->setDefault("GOLES_LOCAL", 0);
        $this->tag->setDefault("GOLES_VISITANTE", 0);
        $this->tag->setDefault("PENALES_LOCAL", 0);
        $this->tag->setDefault("PENALES_VISITANTE", 0);
        $this->tag->setDefault("FECHA", "2016-06-11 20:00:00");
        $this->tag->setDefault("AUD_ESTADO", 1);
    }

    /**
     * Edits a partido
     *
     * @param string $PARTIDOS_ID
     */
    public function editAction($PARTIDOS_ID)
    {
        if (!$this->request->isPost()) {

            $partido = Partidos::findFirstByPARTIDOS_ID($PARTIDOS_ID);
            if (!$partido) {
                $this->flash->error("partido was not found");

                $this->dispatcher->forward(array(
                    'controller' => "partidos",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->PARTIDOS_ID = $partido->PARTIDOS_ID;

            $this->tag->setDefault("PARTIDOS_ID", $partido->PARTIDOS_ID);
            $this->tag->setDefault("LOCAL", $partido->LOCAL);
            $this->tag->setDefault("VISITANTE", $partido->VISITANTE);
            $this->tag->setDefault("GOLES_LOCAL", $partido->GOLES_LOCAL);
            $this->tag->setDefault("GOLES_VISITANTE", $partido->GOLES_VISITANTE);
            $this->tag->setDefault("PENALES_LOCAL", $partido->PENALES_LOCAL);
            $this->tag->setDefault("PENALES_VISITANTE", $partido->PENALES_VISITANTE);
            $this->tag->setDefault("FASES_ID", $partido->FASES_ID);
            $this->tag->setDefault("FECHA", $partido->FECHA);
            $this->tag->setDefault("AUD_ESTADO", $partido->AUD_ESTADO);

        }
    }

    /**
     * Creates a new partido
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "partidos",
                'action' => 'index'
            ));

            return;
        }

        $partido = new Partidos();
        $partido->LOCAL = $this->request->getPost("LOCAL");
        $partido->VISITANTE = $this->request->getPost("VISITANTE");
        $partido->GOLES_LOCAL = $this->request->getPost("GOLES_LOCAL");
        $partido->GOLES_VISITANTE = $this->request->getPost("GOLES_VISITANTE");
        $partido->PENALES_LOCAL = $this->request->getPost("PENALES_LOCAL");
        $partido->PENALES_VISITANTE = $this->request->getPost("PENALES_VISITANTE");
        $partido->FASES_ID = $this->request->getPost("FASES_ID");
        $partido->FECHA = $this->request->getPost("FECHA");
        $partido->AUD_ESTADO = $this->request->getPost("AUD_ESTADO");
        $partido->AUD_FECHA = new \Phalcon\Db\RawValue('now()');
        $auth = $this->auth->getIdentity();
        $partido->AUD_USUARIO = $auth['id'];

        if (!$partido->save()) {
            foreach ($partido->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "partidos",
                'action' => 'new'
            ));

            return;
        }

        $this->flash->success("partido was created successfully");

        $this->dispatcher->forward(array(
            'controller' => "partidos",
            'action' => 'search'
        ));
    }

    /**
     * Saves a partido edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "partidos",
                'action' => 'index'
            ));

            return;
        }

        $PARTIDOS_ID = $this->request->getPost("PARTIDOS_ID");
        $partido = Partidos::findFirstByPARTIDOS_ID($PARTIDOS_ID);

        if (!$partido) {
            $this->flash->error("partido does not exist " . $PARTIDOS_ID);

            $this->dispatcher->forward(array(
                'controller' => "partidos",
                'action' => 'index'
            ));

            return;
        }

        $partido->LOCAL = $this->request->getPost("LOCAL");
        $partido->VISITANTE = $this->request->getPost("VISITANTE");
        $partido->GOLES_LOCAL = $this->request->getPost("GOLES_LOCAL");
        $partido->GOLES_VISITANTE = $this->request->getPost("GOLES_VISITANTE");
        $partido->PENALES_LOCAL = $this->request->getPost("PENALES_LOCAL");
        $partido->PENALES_VISITANTE = $this->request->getPost("PENALES_VISITANTE");
        $partido->FASES_ID = $this->request->getPost("FASES_ID");
        $partido->FECHA = $this->request->getPost("FECHA");
        $partido->AUD_ESTADO = $this->request->getPost("AUD_ESTADO");
        $partido->AUD_FECHA = new \Phalcon\Db\RawValue('now()');
        $auth = $this->auth->getIdentity();
        $partido->AUD_USUARIO = $auth['id'];

        if (!$partido->save()) {

            foreach ($partido->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "partidos",
                'action' => 'edit',
                'params' => array($partido->PARTIDOS_ID)
            ));

            return;
        }

        $this->flash->success("partido was updated successfully");

        $this->completarPronosticos($partido);

        $this->dispatcher->forward(array(
            'controller' => "partidos",
            'action' => 'search'
        ));
    }

    /**
     * COMPLETA LOS PRONOSTICOS DE LOS USUARIOS Y CALCULA LOS PUNTOS OBTENIDOS POR CADA UNO
     * @param Partidos $partido
     */
    public function completarPronosticos(Partidos $partido)
    {
        $auth = $this->auth->getIdentity();
        $usuarios = Usuarios::find("aud_estado = 1 ORDER BY nombre");
        $pronosticos = new Pronosticos();
        $fechaPartidoEnJuego = strtotime('+2 hour', strtotime($partido->FECHA));
        $fechaPartidoEnJuego = date('Y-m-d H:i:s', $fechaPartidoEnJuego);
        //completar ´pronósticos para partidos que no se estén jugando
        if (true) {//$pronosticos->permitidoModificar($fechaPartidoEnJuego)) {
            //Completar pronósticos para partidos que ya iniciaron su juego
            if (!$pronosticos->permitidoModificar($partido->FECHA)) {
                foreach ($usuarios as $usuario) {
                    $pronostico = Pronosticos::findFirst("PARTIDOS_ID = " . $partido->PARTIDOS_ID . " AND USUARIOS_ID = " . $usuario->id);
                    if (!$pronostico) {
                        $pronostico = new Pronosticos();
                        $pronostico->GOLES_LOCAL = 0;
                        $pronostico->GOLES_VISITANTE = 0;
                        $pronostico->PUNTOS_USUARIO = 0;
                        $pronostico->PARTIDOS_ID = $partido->PARTIDOS_ID;
                        $pronostico->USUARIOS_ID = $usuario->id;
                        $pronostico->AUD_USUARIO = $auth['id'];
                        $pronostico->AUD_FECHA = new \Phalcon\Db\RawValue('now()');
                        $pronostico->AUD_ESTADO = 1;
                        $pronostico->save();
                    }
                }
                $partido->calcularPuntosObtenidos($partido->PARTIDOS_ID, $partido->GOLES_LOCAL, $partido->GOLES_VISITANTE);
            }
        }
    }

    /**
     * Deletes a partido
     *
     * @param string $PARTIDOS_ID
     */
    public function deleteAction($PARTIDOS_ID)
    {
        $partido = Partidos::findFirstByPARTIDOS_ID($PARTIDOS_ID);
        if (!$partido) {
            $this->flash->error("partido was not found");

            $this->dispatcher->forward(array(
                'controller' => "partidos",
                'action' => 'index'
            ));

            return;
        }

        if (!$partido->delete()) {

            foreach ($partido->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "partidos",
                'action' => 'search'
            ));

            return;
        }

        $this->flash->success("partido was deleted successfully");

        $this->dispatcher->forward(array(
            'controller' => "partidos",
            'action' => "search"
        ));
    }

}
