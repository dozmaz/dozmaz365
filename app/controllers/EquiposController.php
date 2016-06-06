<?php
namespace Dozmaz365\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Dozmaz365\Models\Equipos;


class EquiposController extends ControllerBase
{
    public $imgNewWidth = 250;
    public $imgNewHeigth = 138;

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
     * Searches for equipos
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Dozmaz365\Models\Equipos', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "EQUIPOS_ID";

        $equipos = Equipos::find($parameters);
        if (count($equipos) == 0) {
            $this->flash->notice("The Buscar did not find any equipos");

            $this->dispatcher->forward(array(
                "controller" => "equipos",
                "action" => "index"
            ));

            return;
        }

        $paginator = new Paginator(array(
            'data' => $equipos,
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
     * Edits a equipo
     *
     * @param string $EQUIPOS_ID
     */
    public function editAction($EQUIPOS_ID)
    {
        if (!$this->request->isPost()) {

            $equipo = Equipos::findFirstByEQUIPOS_ID($EQUIPOS_ID);
            if (!$equipo) {
                $this->flash->error("equipo was not found");

                $this->dispatcher->forward(array(
                    'controller' => "equipos",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->EQUIPOS_ID = $equipo->EQUIPOS_ID;

            $this->tag->setDefault("EQUIPOS_ID", $equipo->EQUIPOS_ID);
            $this->tag->setDefault("NOMBRE", $equipo->NOMBRE);
            $this->tag->setDefault("LOGO", $equipo->LOGO);
            $this->tag->setDefault("AUD_ESTADO", $equipo->AUD_ESTADO);

        }
    }

    /**
     * Creates a new equipo
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "equipos",
                'action' => 'index'
            ));

            return;
        }

        $equipo = new Equipos();
        $equipo->NOMBRE = $this->request->getPost("NOMBRE");
        $equipo->LOGO = "";

        if ($this->request->hasFiles() == true) {
            $archivoExtension = $archivoNombre = "";
            foreach ($this->request->getUploadedFiles() as $file) {
                if (trim($file->getTempName()) != "") {
                    $archivoExtension = $file->getType();
                    $archivoNombre = $file->getName();
                    $archivoPath = $file->getTempName();
                    $tipo = $file->getKey();
                    $archivoNombre = $tipo . "_";
                    // $data = addslashes ( file_get_contents ( $archivoPath ) );
                    $data = addslashes($this->redimensionar_imagen($archivoPath, $this->imgNewWidth, $this->imgNewHeigth, $file->getExtension()));
                    $equipo->LOGO = $data;
                }
            }
        }

        $equipo->AUD_ESTADO = $this->request->getPost("AUD_ESTADO");
        $equipo->AUD_FECHA = new \Phalcon\Db\RawValue('now()');
        $auth = $this->auth->getIdentity();
        $equipo->AUD_USUARIO = $auth['id'];

        if (!$equipo->save()) {
            foreach ($equipo->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "equipos",
                'action' => 'new'
            ));

            return;
        }

        $this->flash->success("equipo was created successfully");

        $this->dispatcher->forward(array(
            'controller' => "equipos",
            'action' => 'index'
        ));
    }

    /**
     * Saves a equipo edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "equipos",
                'action' => 'index'
            ));

            return;
        }

        $EQUIPOS_ID = $this->request->getPost("EQUIPOS_ID");
        $equipo = Equipos::findFirstByEQUIPOS_ID($EQUIPOS_ID);

        if (!$equipo) {
            $this->flash->error("equipo does not exist " . $EQUIPOS_ID);

            $this->dispatcher->forward(array(
                'controller' => "equipos",
                'action' => 'index'
            ));

            return;
        }

        $equipo->NOMBRE = $this->request->getPost("NOMBRE");
        $equipo->LOGO = $this->request->getPost("LOGO");
        $equipo->AUD_ESTADO = $this->request->getPost("AUD_ESTADO");
        $equipo->AUD_FECHA = new \Phalcon\Db\RawValue('now()');
        $auth = $this->auth->getIdentity();
        $equipo->AUD_USUARIO = $auth['id'];

        if (!$equipo->save()) {

            foreach ($equipo->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "equipos",
                'action' => 'edit',
                'params' => array($equipo->EQUIPOS_ID)
            ));

            return;
        }

        $this->flash->success("equipo was updated successfully");

        $this->dispatcher->forward(array(
            'controller' => "equipos",
            'action' => 'index'
        ));
    }

    /**
     * Deletes a equipo
     *
     * @param string $EQUIPOS_ID
     */
    public function deleteAction($EQUIPOS_ID)
    {
        $equipo = Equipos::findFirstByEQUIPOS_ID($EQUIPOS_ID);
        if (!$equipo) {
            $this->flash->error("equipo was not found");

            $this->dispatcher->forward(array(
                'controller' => "equipos",
                'action' => 'index'
            ));

            return;
        }

        if (!$equipo->delete()) {

            foreach ($equipo->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "equipos",
                'action' => 'search'
            ));

            return;
        }

        $this->flash->success("equipo was deleted successfully");

        $this->dispatcher->forward(array(
            'controller' => "equipos",
            'action' => "index"
        ));
    }

    private function redimensionar_imagen($img_original, $img_nueva_anchura, $img_nueva_altura, $tipoImagen = "jpg")
    {
        try {
            switch ($tipoImagen) {
                case "png" :
                    $img = imagecreatefrompng($img_original);
                    $img_nueva_calidad = 9;
                    break;
                case "gif" :
                    $img = imagecreatefromgif($img_original);
                    $img_nueva_calidad = 75;
                    break;
                default :
                    $img = imagecreatefromjpeg($img_original);
                    $img_nueva_calidad = 75;
                    break;
            }
            // crear una imagen nueva
            $thumb = imagecreatetruecolor($img_nueva_anchura, $img_nueva_altura);
            // redimensiona la imagen original copiandola en la imagen
            $size = @getimagesize($img_original);

            if (is_array($size)) {
                $wx = $size [0];
                $hy = $size [1];
            }
            imagecopyresampled($thumb, $img, 0, 0, 0, 0, $img_nueva_anchura, $img_nueva_altura, $wx, $hy);
            // guardar la nueva imagen redimensionada donde indicia $img_nueva
            ob_start();
            switch ($tipoImagen) {
                case "png" :
                    imagepng($thumb, null, $img_nueva_calidad);
                    break;
                case "gif" :
                    imagegif($thumb, null, $img_nueva_calidad);
                    break;
                default :
                    imagejpeg($thumb, null, $img_nueva_calidad);
                    break;
            }
            $imageString = ob_get_clean();
            ImageDestroy($thumb);
            ImageDestroy($img);
        } catch (Exception $e) {
            $imageString = "";
        }
        return $imageString;
    }

}
