<?php
namespace Dozmaz365\Controllers;

/**
 * Display the default index page.
 */
class IndexController extends ControllerBase
{
    /**
     * Default action. Set the public layout (layouts/public.volt)
     */
    public function indexAction()
    {
        $logged_in = is_array($this->auth->getIdentity());
        $this->view->setVar('logged_in', $logged_in);
        if ($logged_in) {
            $this->view->setTemplateBefore('private');
        } else {
            $this->forward('session/login');
            $this->view->setTemplateBefore('public');
        }
    }
}