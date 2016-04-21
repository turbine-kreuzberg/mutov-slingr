<?php

use MutovSlingr\Config\Container;
use MutovSlingr\Config\Router;

require '../../vendor/autoload.php';

$app = new Slim\App();

new Router($app);
new Container($app->getContainer());

$app->run();
