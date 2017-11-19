<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

$loader = require __DIR__.'/../vendor/autoload.php';

if ($_SERVER['APP_DEBUG'] ?? ('prod' !== ($_SERVER['APP_ENV'] ?? 'dev'))) {
    umask(0000);
    Debug::enable();
}

$kernel = new Kernel($_SERVER['APP_ENV'] ?? 'dev', $_SERVER['APP_DEBUG'] ?? ('prod' !== ($_SERVER['APP_ENV'] ?? 'dev')));
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
