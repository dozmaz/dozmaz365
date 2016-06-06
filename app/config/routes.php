<?php
/**
 * Created by PhpStorm.
 * User: gcutipa
 * Date: 06/05/2016
 * Time: 11:08
 */
$router = new Phalcon\Mvc\Router();

$router->add('/confirm/{code}/{email}', array(
    'controller' => 'user_control',
    'action' => 'confirmEmail'
));

$router->add('/reset-password/{code}/{email}', array(
    'controller' => 'user_control',
    'action' => 'resetPassword'
));

return $router;