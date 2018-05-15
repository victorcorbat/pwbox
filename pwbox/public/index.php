<?php
use \Slim\App;
require '../vendor/autoload.php';
$settings = require_once __DIR__ . '/../app/settings.php';
$app = new App($settings);
require_once __DIR__ . '/../app/dependencies.php';
require_once __DIR__ . '/../app/routes.php';

$app->run();