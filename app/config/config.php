<?php

use Phalcon\Logger;
// GRANT ALL PRIVILEGES ON dozmaz365.* To 'admin365'@'localhost' IDENTIFIED BY '.#20-My-Sql-16.#';
return new \Phalcon\Config(array(
    'database' => array(
        'adapter' => 'mysql',
        'host' => 'localhost',
        'username' => 'admin365',
        'password' => '.#20-My-Sql-16.#',
        'dbname' => 'dozmaz365'
    ),
    'application' => array(
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir' => __DIR__ . '/../../app/models/',
        'formsDir' => __DIR__ . '/../../app/forms/',
        'migrationsDir' => __DIR__ . '/../../app/migrations/',
        'viewsDir' => __DIR__ . '/../../app/views/',
        'pluginsDir' => __DIR__ . '/../../app/plugins/',
        'libraryDir' => __DIR__ . '/../../app/library/',
        'cacheDir' => __DIR__ . '/../../app/cache/',
        'baseUri' => '/dozmaz365/',
        'cryptSalt' => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D'
    ),
    'mail' => array(
        'fromName' => 'Guido Cutipa',
        'fromEmail' => 'dozmaz@gmail.com',
        'smtp' => [
            'server' => 'mail.google.com',
            'port' => 587,
            'security' => 'tls',
            'username' => 'dozmaz@gmail.com',
            'password' => ''
        ]
    ),
    'logger' => array(
        'path' => __DIR__ . '/../../app/logs/',
        'format' => '%date% [%type%] %message%',
        'date' => 'D j H:i:s',
        'logLevel' => Logger::DEBUG,
        'filename' => 'application.log'
    ),
    'ldap' => array(
        'servidor' => '172.17.0.4',
        'dominio' => 'pevd.gob.bo',
        'dn' => 'dc=pevd,dc=gob,dc=bo'
    )
));
