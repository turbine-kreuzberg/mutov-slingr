<?php

require '../../vendor/autoload.php';


$app = new Slim\App();

new \MutovSlingr\Config\Router($app);
new \MutovSlingr\Config\Container($app->getContainer());

$app->run();
