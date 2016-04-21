<?php

use MutovSlingr\Config\Container;
use MutovSlingr\Config\Router;

require '../../vendor/autoload.php';


$config = include('../classes/Config/Config.php');
$app = new Slim\App( $config );

new Router( $app );
new Container( $app->getContainer() );

$app->run();