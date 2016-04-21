<?php

use Symfony\Component\Yaml\Parser;


// Default Configurations, Config.yml will be merged over it
$configDefaults = array(
  'api' => array(
    'host' => 'http://www.google.de/api'
  ),
);

// Get Config.yml
if (file_exists( __DIR__.'/Config.yml' )) {
    $parser = new Parser();
    $configEnvironment = $parser->parse( file_get_contents( __DIR__.'/Config.yml' ) );

} else {
    $configEnvironment = array();
}

// Result
return array( 'settings' => array_replace_recursive( $configDefaults, $configEnvironment ) );