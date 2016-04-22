<?php

namespace MutovSlingr\Config;


use MutovSlingr\Controller\ErrorController;
use MutovSlingr\Controller\SlingrController;
use MutovSlingr\Controller\FrontController;
use Slim\App;
use Slim\Container;
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

        // Error handling
        $this->errorHandling();

        // Homepage route
        $this->app->get('/', 'MutovSlingr\Controller\HomeController:indexAction');

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
        $this->app->any( '/slingr/{action}[/{template}[/{outputFormat}[/{download}]]]',
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
            function ( Request $request, Response $response, $args ) use($router) {

                // Controller
                $controller = $this->get( FrontController::class );

                // Action missing
                if (!method_exists( $controller, $args['action'].'Action' )) {
                    throw new \Exception( 'Action '.$args['action'].' does not exist.' );
                }

                // Call action
                $content = $controller->{$args['action'].'Action'}($request, $response, $args  );

                // Return Response Object
                $response->write( $content );
                return $router->responseWithHeaders( $response, $controller->getView('html')->getHeaders() );
            } );
    }

    /**
     *
     */
    protected function errorHandling()
    {
        $router = $this;

        error_reporting(E_ALL);
        ini_set('display_errors', 'on');

        /**
         * Error handling
         *
         * @param Container $container
         * @return callable
         */
        $this->app->getContainer()['errorHandler'] = function ( Container $container) use($router)  {
            return function (Request $request, Response $response, \Exception $exception) use ( $container,$router) {

                $controller = $container->get(ErrorController::class);
                $content = $controller->errorAction($exception);

                $response = $container['response']->withStatus(500)
                  ->write($content);

                return $router->responseWithHeaders( $response, $controller->getView()->getHeaders() );
            };
        };

        /**
         * Page not found handling
         *
         * @param Container $container
         * @return callable
         */
        $this->app->getContainer()['notFoundHandler'] = function (Container $container) use($router) {
            return function (Request $request, Response $response) use ($container,$router) {

                $controller = $container->get(ErrorController::class);
                $content = $controller->notFoundAction();

                $response = $container['response']->withStatus(404)
                  ->write($content);

                return $router->responseWithHeaders( $response, $controller->getView()->getHeaders() );
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
