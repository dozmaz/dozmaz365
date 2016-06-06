<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerNamespaces([
    'Dozmaz365\Models'      => $config->application->modelsDir,
    'Dozmaz365\Controllers' => $config->application->controllersDir,
    'Dozmaz365\Forms'       => $config->application->formsDir,
    'Dozmaz365'             => $config->application->libraryDir
]);

$loader->register();
