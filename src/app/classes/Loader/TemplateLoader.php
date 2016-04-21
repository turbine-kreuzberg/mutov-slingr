<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 17:04
 */

namespace MutovSlingr\Loader;


use Slim\Interfaces\CollectionInterface;

class TemplateLoader
{

    private $config;

    /**
     * @param $config
     */
    public function __construct(CollectionInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param $templateFileName
     *
     * @return mixed|string
     * @throws \ErrorException
     */
    public function loadTemplate($templateFileName)
    {

        $fullPath = $this->config->get('template_folder') . '/' . $templateFileName;

        if (!file_exists($fullPath)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not exist.', $templateFileName));
        }

        $contents = file_get_contents($fullPath);

        $contents = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \ErrorException(sprintf('Template parsing failed: %s', json_last_error_msg()));
        }

        return $contents;

    }
}
