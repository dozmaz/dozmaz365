<?php
/**
 * Created by PhpStorm.
 * User: gcutipa
 * Date: 06/05/2016
 * Time: 11:31
 */

namespace Dozmaz365\Auth;

use Phalcon\Mvc\User\Component;
use Dozmaz365\Models\Usuarios;
use Dozmaz365\Models\RecordarTokens;
use Dozmaz365\Models\ConexionesExitosas;
use Dozmaz365\Models\ConexionesFallidas;

/**
 * Dozmaz365\Auth\Auth
 * Manages Authentication/Identity Management in dozmaz365
 */
class Auth extends Component
{
    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolean
     * @throws Exception
     */
    public function check($credentials)
    {

        // Check if the user exist
        $usuario = Usuarios::findFirstByEmail($credentials['email']);
        if ($usuario == false) {
            $this->registerUserThrottling(0);
            throw new Exception('Incorrecta combinación email/password.');
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $usuario->password)) {
            $this->registerUserThrottling($usuario->id);
            throw new Exception('Incorrecta combinación email/password.');
        }

        // Check if the user was flagged
        $this->checkUserFlags($usuario);

        // Register the successful login
        $this->saveSuccessLogin($usuario);

        // Check if the remember me was selected
        if (isset($credentials['remember'])) {
            $this->createRememberEnvironment($usuario);
        }

        $this->session->set('auth-identity', array(
            'id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'perfil' => $usuario->perfil->nombre
        ));
    }

    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolean
     * @throws Exception
     */
    public function checkLdap($credentials)
    {
        $this->session->set('fotoPerfil', '');
        $this->session->set('fotoPerfil2','');
        // Check if the user exist
        $usuario = Usuarios::findFirstByEmail($credentials['email']."@pevd.gob.bo");
        if ($usuario == false) {
            $this->check($credentials);
        }else {
            $credentials['email'].="@pevd.gob.bo";
            $ldap = $this->getDI()->get("config")->ldap;

            $ldapServidor = $ldap->servidor;
            $ldapDominio = $ldap->dominio;
            $ldapDN = $ldap->dn;

            if (strpos($credentials['email'], '@') !== false) {
                $credencialEmail = explode("@", $credentials['email']);
                $email = $credencialEmail [0];
                $ldapDominio = $credencialEmail [1];
            }

            $autenticadoLDAP = NULL;
            if (trim($ldapServidor) != "") {
                /* autenticacion ldap */
                $conectadoLDAP = ldap_connect($ldapServidor);
                if ($conectadoLDAP) {
                    ldap_set_option($conectadoLDAP, LDAP_OPT_PROTOCOL_VERSION, 3);
                    ldap_set_option($conectadoLDAP, LDAP_OPT_REFERRALS, 0);
                    $autenticadoLDAP = ldap_bind($conectadoLDAP, $email . "@" . $ldapDominio, $credentials['password']);
                    if ($autenticadoLDAP) {
                        $this->session->set('fotoPerfil', '');
                        $this->session->set('fotoPerfil2', '');
                        try {
                            $filter = "(sAMAccountName=" . $email . ")";
                            $read = ldap_search($conectadoLDAP, $ldapDN, $filter);
                            $info = ldap_get_entries($conectadoLDAP, $read);
                            $ii = 0;
                            for ($i = 0; $ii < $info [$i] ["count"]; $ii++) {
                                $data = $info [$i] [$ii];
                                switch ($data) {
                                    case "thumbnailphoto" :
                                        $this->session->set('fotoPerfil', '<img class="img-circle profile_img" src="data:image/jpeg;base64,' . base64_encode($info [$i] [$data] [0]) . '"/>');
                                        $this->session->set('fotoPerfil2', '<img class="img-circle" style="width:35px;height: 35px;" src="data:image/jpeg;base64,' . base64_encode($info [$i] [$data] [0]) . '"/>');
                                        break;
                                }
                            }
                        } catch (Exception $e) {
                        }
                    } else {
                        throw new Exception('Incorrecta combinación email/password');
                    }
                    ldap_close($conectadoLDAP);
                }
            } else {
                throw new Exception('No se puede conectar con el servidor');
            }

            // Check if the user was flagged
            $this->checkUserFlags($usuario);

            // Register the successful login
            $this->saveSuccessLogin($usuario);

            // Check if the remember me was selected
            if (isset($credentials['remember'])) {
                $this->createRememberEnvironment($usuario);
            }

            $this->session->set('auth-identity', array(
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'perfil' => $usuario->perfil->nombre
            ));
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Dozmaz365\Models\Usuarios $usuario
     * @throws Exception
     */
    public function saveSuccessLogin($usuario)
    {
        $successLogin = new ConexionesExitosas();
        $successLogin->usuariosId = $usuario->id;
        $successLogin->direccionIp = $this->request->getClientAddress();
        $successLogin->agenteUsuario = $this->request->getUserAgent();
        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();
            throw new Exception($messages[0]);
        }
    }

    /**
     * Implements login throttling
     * Reduces the effectiveness of brute force attacks
     *
     * @param int $usuarioId
     */
    public function registerUserThrottling($usuarioId)
    {
        $failedLogin = new ConexionesFallidas();
        $failedLogin->usuariosId = $usuarioId;
        $failedLogin->direccionIp = $this->request->getClientAddress();
        $failedLogin->intento = time();
        $failedLogin->save();

        $attempts = ConexionesFallidas::count(array(
            'direccionIp = ?0 AND intento >= ?1',
            'bind' => array(
                $this->request->getClientAddress(),
                time() - 3600 * 6
            )
        ));

        switch ($attempts) {
            case 1:
            case 2:
                // no delay
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Dozmaz365\Models\Usuarios $usuario
     */
    public function createRememberEnvironment(Usuarios $usuario)
    {
        $agenteUsuario = $this->request->getUserAgent();
        $token = md5($usuario->email . $usuario->password . $agenteUsuario);

        $remember = new RecordarTokens();
        $remember->usuariosId = $usuario->id;
        $remember->token = $token;
        $remember->agenteUsuario = $agenteUsuario;

        if ($remember->save() != false) {
            $expire = time() + 86400 * 8;
            $this->cookies->set('RMU', $usuario->id, $expire);
            $this->cookies->set('RMT', $token, $expire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the cookies
     *
     * @return \Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $usuarioId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $usuario = Usuarios::findFirstById($usuarioId);
        if ($usuario) {

            $agenteUsuario = $this->request->getUserAgent();
            $token = md5($usuario->email . $usuario->password . $agenteUsuario);

            if ($cookieToken == $token) {

                $remember = RecordarTokens::findFirst(array(
                    'usuariosId = ?0 AND token = ?1',
                    'bind' => array(
                        $usuario->id,
                        $token
                    )
                ));
                if ($remember) {

                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->createdAt) {

                        // Check if the user was flagged
                        $this->checkUserFlags($usuario);

                        // Register identity
                        $this->session->set('auth-identity', array(
                            'id' => $usuario->id,
                            'nombre' => $usuario->nombre,
                            'perfil' => $usuario->perfil->nombre
                        ));

                        // Register the successful login
                        $this->saveSuccessLogin($usuario);

                        return $this->response->redirect('usuarios');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect('session/login');
    }

    /**
     * Checks if the user is banned/inactive/suspended
     *
     * @param \Dozmaz365\Models\Usuarios $usuario
     * @throws Exception
     */
    public function checkUserFlags(Usuarios $usuario)
    {
        if ($usuario->activo != 'Y') {
            throw new Exception('El usuario está inactivo');
        }

        if ($usuario->bloqueado != 'N') {
            throw new Exception('El usuario está prohibido');
        }

        if ($usuario->suspendido != 'N') {
            throw new Exception('El usuario está suspendido');
        }
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-identity');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['nombre'];
    }

    /**
     * Retorna el nombre del perfil de l usuario
     * @return string
     */
    public function getProfile()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['perfil'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        if ($this->cookies->has('RMT')) {
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     * @throws Exception
     */
    public function authUserById($id)
    {
        $user = Usuarios::findFirstById($id);
        if ($user == false) {
            throw new Exception('El usuario no existe');
        }

        $this->checkUserFlags($user);

        $this->session->set('auth-identity', array(
            'id' => $user->id,
            'nombre' => $user->nombre,
            'perfil' => $user->perfil->nombre
        ));
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return \Dozmaz365\Models\Usuarios
     * @throws Exception
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');
        if (isset($identity['id'])) {

            $user = Usuarios::findFirstById($identity['id']);
            if ($user == false) {
                throw new Exception('El usuario no existe');
            }

            return $user;
        }

        return false;
    }
}
