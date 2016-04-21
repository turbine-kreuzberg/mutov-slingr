<?php

namespace MutovSlingr\Config;


use MutovSlingr\Controller\ErrorController;
use MutovSlingr\Controller\SlingrController;
use MutovSlingr\Controller\FrontController;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class Router
{

    /**
     * @var App
     */
    protected $app;

    /**
     * @param App $app
     */
    public function __construct( App $app )
    {

        $this->app = $app;

        $this->errorHandling();

        $this->app->get( '/', 'MutovSlingr\Controller\HomeController:indexAction' );

        $this->app->get( '/test', 'MutovSlingr\Controller\HomeController:testAction' );

        $this->app->get( '/process', 'MutovSlingr\Controller\HomeController:processAction' );

        // Slingr route
        $this->app->any( '/slingr/{action}[/{template}]',
          function ( Request $request, Response $response, $args ) {

              // Controller
              $controller = $this->get( SlingrController::class );

              // Action missing
              if (!method_exists( $controller, $args['action'].'Action' )) {
                  throw new \Exception( 'Action '.$args['action'].' does not exist.' );
              }

              // Call action
              $content = $controller->{$args['action'].'Action'}( $args, $request );

              // Return Response Object
              return $response->write( $content )->withHeader( 'Content-Type',
                $controller->getView()->getContentType() );
          } );


        // Front route
        $this->app->get( '/front/{action}[/{template}]',
            function ( Request $request, Response $response, $args ) {

                // Controller
                $controller = $this->get( FrontController::class );

                // Action missing
                if (!method_exists( $controller, $args['action'].'Action' )) {
                    throw new \Exception( 'Action '.$args['action'].' does not exist.' );
                }

                // Call action
                $content = $controller->{$args['action'].'Action'}( $args );

                // Return Response Object
                return $response->write( $content )->withHeader( 'Content-Type',
                    $controller->getView()->getContentType() );
            } );

    }

    /**
     *
     */
    protected function errorHandling()
    {
        error_reporting( E_ALL );
        ini_set( 'display_errors', 'on' );

        /**
         * Error handling
         *
         * @param \Slim\Container $c
         * @return callable
         */
        $this->app->getContainer()['errorHandler'] = function ( $c ) {
            return function ( $request, $response, \Exception $exception ) use ( $c ) {

                $controller = new ErrorController();
                $content = $controller->errorAction( $exception );

                return $c['response']->withStatus( 500 )
                  ->withHeader( 'Content-Type', $controller->getView()->getContentType() )
                  ->write( $content );
            };
        };

        /**
         * Page not found handling
         *
         * @param \Slim\Container $c
         * @return callable
         */
        $this->app->getContainer()['notFoundHandler'] = function ( $c ) {
            return function ( $request, $response ) use ( $c ) {

                $controller = new ErrorController();
                $content = $controller->notFoundAction();

                return $c['response']->withStatus( 404 )
                  ->withHeader( 'Content-Type', $controller->getView()->getContentType() )
                  ->write( $content );
            };
        };
    }

}
