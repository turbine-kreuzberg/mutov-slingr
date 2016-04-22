<?php

namespace MutovSlingr\Config;


use Interop\Container\ContainerInterface;
use MutovSlingr\Controller\SlingrController;
use MutovSlingr\Controller\FrontController;
use MutovSlingr\Loader\TemplateLoader;
use MutovSlingr\Model\Api;
use MutovSlingr\Processor\TemplateProcessor;
use MutovSlingr\Views\ViewJson;

class Container
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct( ContainerInterface $container )
    {

        $container[TemplateProcessor::class] = function ( \Slim\Container $container ) {
            $class = new TemplateProcessor(
                $container->get( Api::class ),
                $container->get('settings')
            );

            return $class;
        };

        $container[ViewJson::class] = function ( \Slim\Container $container ) {
            $class = new ViewJson();

            return $class;
        };

        $container[Api::class] = function ( \Slim\Container $container ) {
            $class = new Api( $container->get( 'settings' ));

            return $class;
        };

        $container[TemplateLoader::class] = function ( \Slim\Container $container ) {
            $class = new TemplateLoader( $container->get( 'settings' ));

            return $class;
        };


        $container[SlingrController::class] = function ( \Slim\Container $container ) {
            $class = new SlingrController();

            $class->setProcessor( $container->get( TemplateProcessor::class ) );
            $class->setTemplateLoader( $container->get( TemplateLoader::class) );
            $class->setConfig( $container->get( 'settings' ) );

            return $class;
        };


        $container[FrontController::class] = function ( \Slim\Container $container ) {
            $class = new FrontController();

            $class->setConfig( $container->get( 'settings' ) );

            return $class;
        };

    }
}
