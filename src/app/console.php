#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use MutovSlingr\Config\Container;
use MutovSlingr\Console\Command\ApiCommand;
use MutovSlingr\Model\Api;
use Symfony\Component\Console\Application;

$config = include 'classes/Config/Config.php';
$app = new Slim\App($config);
new Container($app->getContainer());

$application = new Application();
$application->add(new ApiCommand($app->getContainer()->get(Api::class), $app->getContainer()->get('settings')));
$application->run();
