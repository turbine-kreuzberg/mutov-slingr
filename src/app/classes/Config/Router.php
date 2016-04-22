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
    public function __construct(App $app)
    {
        $this->app = $app;
        $router = $this;

        $this->errorHandling();

        $this->app->get('/', 'MutovSlingr\Controller\HomeController:indexAction');

        $this->app->get('/test', 'MutovSlingr\Controller\HomeController:testAction');

        $this->app->get('/process', 'MutovSlingr\Controller\HomeController:processAction');


        // Slingr routes
        $this->app->any( '/slingr/onfly[/{outputFormat}]',
            function ( Request $request, Response $response, $args ) {

                /** @var SlingrController $controller */
                $controller = $this->get(SlingrController::class);

                // Call action
                $content = $controller->onflyAction( $args, $request );

                // Return Response Object
                return $response->write( $content )->withHeader( 'Content-Type',
                    $controller->getView()->getContentType() );
            } );

        
        // Slingr route
        $this->app->any( '/slingr/{action}[/{template}[/{outputFormat}]]',
          function ( Request $request, Response $response, $args ) use($router) {

                /** @var SlingrController $controller */
                $controller = $this->get(SlingrController::class);

                // Action missing
                if (!method_exists($controller, $args['action'] . 'Action')) {
                    throw new \Exception('Action ' . $args['action'] . ' does not exist.');
                }

              // Call action
              $content = $controller->{$args['action'].'Action'}( $args, $request );

              // Return Response Object
              $response->write( $content );

              return $router->responseWithHeaders( $response, $controller->getView()->getHeaders() );
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
                $content = $controller->{$args['action'].'Action'}($request, $response, $args  );

                // Return Response Object
                return $response->write( $content )->withHeader( 'Content-Type', $controller->getView()->getContentType() );
            } );
    }

    /**
     *
     */
    protected function errorHandling()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 'on');

        /**
         * Error handling
         *
         * @param \Slim\Container $c
         * @return callable
         */
        $this->app->getContainer()['errorHandler'] = function ($c) {
            return function ($request, $response, \Exception $exception) use ($c) {

                $controller = new ErrorController();
                $content = $controller->errorAction($exception);

                return $c['response']->withStatus(500)
                    ->withHeader('Content-Type', $controller->getView()->getContentType())
                    ->write($content);
            };
        };

        /**
         * Page not found handling
         *
         * @param \Slim\Container $c
         * @return callable
         */
        $this->app->getContainer()['notFoundHandler'] = function ($c) {
            return function ($request, $response) use ($c) {

                $controller = new ErrorController();
                $content = $controller->notFoundAction();

                return $c['response']->withStatus(404)
                    ->withHeader('Content-Type', $controller->getView()->getContentType())
                    ->write($content);
            };
        };
    }

    /**
     * Set an array of headers to a response
     *
     * @param Response $response
     * @param array    $headers
     * @return Response|static
     */
    public function responseWithHeaders( $response, $headers )
    {
        if (!empty($headers)) {
            foreach ($headers as $field => $value) {
                $response = $response->withHeader( $field, $value );
            }
        }

        return $response;
    }

}
