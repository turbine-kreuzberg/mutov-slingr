<?php

namespace MutovSlingr\Config;


use Interop\Container\ContainerInterface;
use MutovSlingr\Controller\ErrorController;
use MutovSlingr\Controller\SlingrController;
use MutovSlingr\Controller\FrontController;
use MutovSlingr\Loader\TemplateLoader;
use MutovSlingr\Model\Api;
use MutovSlingr\Processor\TemplateProcessor;
use MutovSlingr\Views\ViewHtml;
use MutovSlingr\Views\ViewJson;
use MutovSlingr\Views\ViewPhp;
use MutovSlingr\Views\ViewXml;

class Container
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $container[TemplateProcessor::class] = function (\Slim\Container $container) {
            $class = new TemplateProcessor(
                $container->get(Api::class),
                $container->get('settings')
            );

            return $class;
        };

        $container[ViewJson::class] = function (\Slim\Container $container) {
            return new ViewJson();
        };

        $container[ViewPhp::class] = function (\Slim\Container $container) {
            return new ViewPhp();
        };

        $container[ViewHtml::class] = function (\Slim\Container $container) {
            return new ViewHtml();
        };

        $container[ViewXml::class] = function (\Slim\Container $container) {
            return new ViewXml();
        };

        $container[Api::class] = function (\Slim\Container $container) {
            $class = new Api($container->get('settings'));

            return $class;
        };

        $container[TemplateLoader::class] = function (\Slim\Container $container) {
            $class = new TemplateLoader($container->get('settings'));

            return $class;
        };

        $container[SlingrController::class] = function (\Slim\Container $container) {
            $class = new SlingrController();

            $class->setProcessor($container->get(TemplateProcessor::class));
            $class->setTemplateLoader($container->get(TemplateLoader::class));
            $class->setConfig($container->get('settings'));

            $views = [
                'json' => $container->get(ViewJson::class),
                'php' => $container->get(ViewPhp::class),
                'html' => $container->get(ViewHtml::class),
                'xml' => $container->get(ViewXml::class),
            ];

            $class->setViews($views);

            return $class;
        };

        $container[ErrorController::class] = function (\Slim\Container $container) {
            $class = new ErrorController();

            $views = [
              'json' => $container->get(ViewJson::class),
              'php' => $container->get(ViewPhp::class),
              'html' => $container->get(ViewHtml::class),
            ];

            $class->setViews($views);

            return $class;
        };

        $container[FrontController::class] = function (\Slim\Container $container) {
            $class = new FrontController();

            $class->setConfig($container->get('settings'));

            $views = [
                'html' => $container->get(ViewHtml::class),
            ];

            $class->setViews($views);

            return $class;
        };
    }
}
