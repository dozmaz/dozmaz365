<?php
namespace Dozmaz365\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class ControllerBase extends Controller
{
    protected function initialize()
    {
        $this->view->setVar('title', 'Pronosticos');
        $this->view->setVar('fotoPerfil', $this->session->get('fotoPerfil'));
        $this->view->setVar('fotoPerfil2', $this->session->get('fotoPerfil2'));

        $auth = $this->auth->getIdentity();
    }

    /**
     * redirecciona a la $uri solicitada
     * @param string $uri
     */
    protected function forward($uri) {
        return $this->response->redirect ( $uri );
    }

    /**
     * Execute before the router so we can determine if this is a private controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();

        // Only check permissions on private controllers
        if ($this->acl->isPrivate($controllerName)) {

            // Get the current identity
            $identity = $this->auth->getIdentity();

            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {

                $this->flash->notice('Usted no tiene acceso a este m&oacute;dulo');

                $dispatcher->forward(array(
                    'controller' => 'index',
                    'action' => 'index'
                ));
                return false;
            }

            // Check if the user have permission to the current option
            $actionName = $dispatcher->getActionName();
            if (!$this->acl->isAllowed($identity['profile'], $controllerName, $actionName)) {

                $this->flash->notice('Usted no tiene acceso a este m&oacute;dulo: ' . $controllerName . ':' . $actionName);

                if ($this->acl->isAllowed($identity['profile'], $controllerName, 'index')) {
                    $dispatcher->forward(array(
                        'controller' => $controllerName,
                        'action' => 'index'
                    ));
                } else {
                    $dispatcher->forward(array(
                        'controller' => 'user_control',
                        'action' => 'index'
                    ));
                }

                return false;
            }
        }
    }
}
