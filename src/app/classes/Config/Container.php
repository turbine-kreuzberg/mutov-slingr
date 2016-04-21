<?php

namespace MutovSlingr\Config;


use Interop\Container\ContainerInterface;
use MutovSlingr\Controller\SlingrController;
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
            $class = new TemplateProcessor();

            return $class;
        };

        $container[ViewJson::class] = function ( \Slim\Container $container ) {
            $class = new ViewJson();

            return $class;
        };

        $container[Api::class] = function ( \Slim\Container $container ) {
            $class = new Api();

            return $class;
        };

        $container[SlingrController::class] = function ( \Slim\Container $container ) {
            $class = new SlingrController();

            $class->setProcessor( $container->get( TemplateProcessor::class ) );

            return $class;
        };

    }
}
