<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 17:04
 */

namespace MutovSlingr\Loader;


class TemplateLoader
{

    private $config;

    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function loadTemplate($templateFileName)
    {

        $fullPath = $this->config['template']['folder'].'/'.$templateFileName;
        $contents = file_get_contents($fullPath);

        $contents = json_decode($contents, true);

        return $contents;

    }
}