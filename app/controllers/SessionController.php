<?php
namespace Dozmaz365\Controllers;

use Dozmaz365\Forms\LoginForm;
use Dozmaz365\Forms\SignUpForm;
use Dozmaz365\Forms\ForgotPasswordForm;
use Dozmaz365\Auth\Exception as AuthException;
use Dozmaz365\Models\Users;
use Dozmaz365\Models\ResetPasswords;
use Dozmaz365\Models\Usuarios;

/**
 * Class SessionController
 * Maneja acciones de sesion no autenticada como login/logout, registro de usuarios, y contraseñas olvidadas
 */
class SessionController extends \Phalcon\Mvc\Controller
{

    public function initialize()
    {
        $this->view->setTemplateBefore('public');
    }

    public function indexAction()
    {

    }

    /**
     * Permite a un usuario registrarse en el sistema
     */
    public function signupAction()
    {
        $form = new SignUpForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) != false) {

                $user = new Usuarios();

                $user->assign(array(
                    'nombre' => $this->request->getPost('nombre', 'striptags'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->security->hash($this->request->getPost('password')),
                    'perfilesId' => 2,
                    'aud_usuario' => 1
                ));

                if ($user->save()) {
                    return $this->dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ));
                }

                $this->flash->error($user->getMessages());
            }
        }

        $this->view->form = $form;
    }

    /**
     * Inicia sesion en el backend de administración
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function loginAction()
    {
        $form = new LoginForm();

        try {

            if (!$this->request->isPost()) {

                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {

                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->auth->checkLdap(array(
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ));
                    return $this->response->redirect('pronosticos');
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $form;
    }

    /**
     * Muestra el formulario de olvidó su contraseña
     */
    public function forgotPasswordAction()
    {
        $form = new ForgotPasswordForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = Users::findFirstByEmail($this->request->getPost('email'));
                if (!$user) {
                    $this->flash->success('There is no account associated to this email');
                } else {

                    $resetPassword = new ResetPasswords();
                    $resetPassword->usuariosId = $user->id;
                    if ($resetPassword->save()) {
                        $this->flash->success('Success! Please check your messages for an email reset password');
                    } else {
                        foreach ($resetPassword->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * Cierra la sesion de un usuario
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function logoutAction()
    {
        $this->auth->remove();

        return $this->response->redirect('index');
    }
}

