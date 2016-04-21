<?php

use Symfony\Component\Yaml\Parser;


// Default Configurations, Config.yml will be merged over it
$configDefaults = array(
    'api_host' => 'http://api.mutov-slingr.votum-local.de/process.php?request=data',
    'template_folder' => '/var/www/mutov-slingr/app/var',

    'data_generator_export_configuration' => [
        'export' => [
            'type' => 'JSON',
            'settings' => [
                'stripWhitespace' => false,
                'dataStructureFormat' => "simple"
            ]
        ]
    ],
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
