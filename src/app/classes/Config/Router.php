<?php

namespace MutovSlingr\Config;


use Slim\App;

class Router{

    /**
     * @param App $app
     */
    public function __construct(App $app)
    {
        $app->get('/', 'MutovSlingr\Controller\HomeController:indexAction');

        $app->get('/test', 'MutovSlingr\Controller\HomeController:testAction');

        $app->get('/process', 'MutovSlingr\Controller\HomeController:processAction');

        $app->getContainer()['errorHandler'] = function ( $c ) {
            return function ( $request, $response, \Exception $exception ) use ( $c) {

                return $c['response']->withStatus( 500 )
                    ->withHeader( 'Content-Type', 'text/html' )
                    ->write($exception->getMessage() );
            };
        };
    }

}
