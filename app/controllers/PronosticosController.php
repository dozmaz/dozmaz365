<?php
namespace Dozmaz365\Controllers;

use Dozmaz365\Models\Partidos;
use Dozmaz365\Models\Pronosticos;
use Dozmaz365\Models\Usuarios;

class PronosticosController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->setTemplateBefore('private');
        $this->view->setVar('title', 'Pronosticos');
    }

    /**
     * Index action
     */
    public function indexAction($fechaSeleccionada = '')
    {
        $partidos = new Partidos();
        // OBTENER TODAS LAS FECHAS DE LOS PARTIDOS
        $fechas = $partidos->fechasPartidos();
        $this->view->setVar("fechas", $fechas);

        if ($fechaSeleccionada == "" || !isset($fechaSeleccionada)) {
            $fechaSeleccionada = date("Y-m-d");
        }

        $pronosticos = new Pronosticos();
        if ($pronosticos->permitidoModificar($fechaSeleccionada . " 23:59:59")) {
            //OBTENER TODOS LOS PARTIDOS DE UNA FECHA
            $partidosDelDia = Partidos::find("FECHA LIKE '$fechaSeleccionada%'");

            //SI LA FECHA SELECCIONADA NO TIENE PARTIDOS HABILITADOS SE BUSCA LA SIGUIENTE FECHA DISPONIBLE
            if ($partidosDelDia && count($partidosDelDia) <= 0) {
                foreach ($fechas as $fecha) {
                    if ($pronosticos->permitidoModificar($fecha->FECHA_PARTIDO)) {
                        $fechaSeleccionada = $fecha->FECHA_PARTIDO;
                        break;
                    }
                }
                $partidosDelDia = Partidos::find("FECHA LIKE '$fechaSeleccionada%'");
            }

            $this->view->setVar("partidosDelDia", $partidosDelDia);
            $this->view->setVar("fechaSeleccionada", $fechaSeleccionada);
            $this->view->setVar("pronosticos", $pronosticos);

            $auth = $this->auth->getIdentity();

            //SE CARGAN TODOS LOS PRONOSTICOS REGISTRADOS PARA LOS PARTIDOS DEL DÍA A MOSTRAR
            foreach ($partidosDelDia as $partido) {
                $pronostico = Pronosticos::findFirst("PARTIDOS_ID = " . $partido->PARTIDOS_ID . " AND USUARIOS_ID = " . $auth['id']);
                if (!$pronostico) {
                    $this->tag->displayTo("LOCAL_" . $partido->PARTIDOS_ID . "_" . $partido->LOCAL, 0);
                    $this->tag->displayTo("VISITANTE_" . $partido->PARTIDOS_ID . "_" . $partido->VISITANTE, 0);
                } else {
                    $this->tag->displayTo("LOCAL_" . $partido->PARTIDOS_ID . "_" . $partido->LOCAL, $pronostico->GOLES_LOCAL);
                    $this->tag->displayTo("VISITANTE_" . $partido->PARTIDOS_ID . "_" . $partido->VISITANTE, $pronostico->GOLES_VISITANTE);
                }

            }
        }
    }

    /**
     * Grabar los pronósticos de los partidos
     */
    public function saveAction()
    {
        $request = $this->request->getPost();
        $auth = $this->auth->getIdentity();
        $errores = false;
        foreach ($request as $clave => $goles) {
            if ($clave != "fechaSeleccionada") {
                $campos = explode("_", $clave);
                $campo_goles = "GOLES_" . $campos[0];
                $partido_id = $campos[1];
                $equipo_id = $campos[2];
                $pronostico = new Pronosticos();
                $partido = Partidos::findFirst("PARTIDOS_ID = " . $partido_id);
                if ($partido && $pronostico->permitidoModificar($partido->FECHA)) {
                    $pronostico = Pronosticos::findFirst("PARTIDOS_ID = " . $partido_id . " AND USUARIOS_ID = " . $auth['id']);
                    if (!$pronostico) {
                        $pronostico = new Pronosticos();
                        $pronostico->USUARIOS_ID = $auth['id'];
                        $pronostico->PARTIDOS_ID = $partido_id;
                        $pronostico->GOLES_LOCAL = 0;
                        $pronostico->GOLES_VISITANTE = 0;
                        $pronostico->PUNTOS_USUARIO = 0;
                    }
                    $pronostico->$campo_goles = $goles;
                    $pronostico->AUD_ESTADO = 1;
                    $pronostico->AUD_FECHA = new \Phalcon\Db\RawValue('now()');
                    $pronostico->AUD_USUARIO = $auth['id'];
                    if (!$pronostico->save()) {
                        foreach ($pronostico->getMessages() as $message) {
                            $this->flash->error(( string )$message);
                        }
                        $errores = true;
                    }
                } elseif (!$partido) {
                    $this->flash->error("No existe el partido");
                    $errores = true;
                } else {
                    $this->flash->error("Ya no se pueden registrar pron&oacute;sticos para este partido");
                    $errores = true;
                }
            }
        }
        if (!$errores) {
            $this->flash->success("Pron&oacute;stico registrado");
        }
        $this->dispatcher->forward(array(
            'controller' => "pronosticos",
            'action' => 'index',
            'params' => array($request["fechaSeleccionada"])
        ));
    }

    /**
     * Lista todos los pronósticos registrados para los partidos
     */
    public function resultadosAction()
    {
        $auth = $this->auth->getIdentity();
        $usuarios = Usuarios::find("aud_estado = 1 ORDER BY nombre");
        $partidos = Partidos::find("AUD_ESTADO = 1 ORDER BY FECHA DESC");
        $pronosticos = new Pronosticos();
        $pronosticosUsuarios = array();
        foreach ($partidos as $partido) {
            //mostrar los partidos que ya iniciaron su juego
            if (!$pronosticos->permitidoModificar($partido->FECHA)) {
                $pronosticosUsuarios[$partido->PARTIDOS_ID]["NOMBRE"] = $partido->getLocal()->NOMBRE . " vs. " . $partido->getVisitante()->NOMBRE;
                $pronosticosUsuarios[$partido->PARTIDOS_ID]["LOCAL"] = $partido->GOLES_LOCAL;
                $pronosticosUsuarios[$partido->PARTIDOS_ID]["VISITANTE"] = $partido->GOLES_VISITANTE;
                $pronosticosUsuarios[$partido->PARTIDOS_ID]["PUNTOS"] = -1;
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
                    $pronosticosUsuarios[$partido->PARTIDOS_ID . "_" . $usuario->id]["USUARIO_ID"] = $usuario->id;
                    $pronosticosUsuarios[$partido->PARTIDOS_ID . "_" . $usuario->id]["NOMBRE"] = $usuario->nombre;
                    $pronosticosUsuarios[$partido->PARTIDOS_ID . "_" . $usuario->id]["LOCAL"] = $pronostico->GOLES_LOCAL;
                    $pronosticosUsuarios[$partido->PARTIDOS_ID . "_" . $usuario->id]["VISITANTE"] = $pronostico->GOLES_VISITANTE;
                    $pronosticosUsuarios[$partido->PARTIDOS_ID . "_" . $usuario->id]["PUNTOS"] = $pronostico->PUNTOS_USUARIO;
                }
            }
        }
        $this->view->setVar("usuario_id", $auth['id']);
        $this->view->setVar("pronosticosUsuarios", $pronosticosUsuarios);
    }

    /**
     * Lista los puntos obtenidos por los usuarios
     */
    public function puntuacionesAction()
    {
        $auth = $this->auth->getIdentity();
        $posiciones = Pronosticos::sum(
            array(
                "column" => "PUNTOS_USUARIO",
                "group" => "USUARIOS_ID",
                "order" => "sumatory DESC"
            )
        );
        $puntoMaximo = -1;
        $puntuaciones = array();
        foreach ($posiciones as $posicion) {
            if ($puntoMaximo < 0) {
                $puntoMaximo = $posicion->sumatory;
            }
            $usuario = Usuarios::findFirst("id = " . $posicion->USUARIOS_ID);
            $puntuaciones[$posicion->USUARIOS_ID]["NOMBRE"] = $usuario->nombre;
            $puntuaciones[$posicion->USUARIOS_ID]["PUNTOS"] = $posicion->sumatory;
            $puntuaciones[$posicion->USUARIOS_ID]["PORCENTAJE"] = ($posicion->sumatory / $puntoMaximo) * 100;
        }
        $this->view->setVar("usuario_id", $auth['id']);
        $this->view->setVar("puntuaciones", $puntuaciones);
    }
}

