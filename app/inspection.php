<?php

$autoloader = require __DIR__ . '/../src/autoload.php';

if (!$autoloader()) {
    die('uh-oh');
}

$app = new Stratedge\Inspection\Console\Application();

$app->run();
