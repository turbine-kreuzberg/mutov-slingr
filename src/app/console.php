#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use MutovSlingr\Console\Command\ApiCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new ApiCommand());
$application->run();
