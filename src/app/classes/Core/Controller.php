<?php

namespace MutovSlingr\Core;

/**
 * Class Controller
 *
 * Base controller abstract for all controllers
 *
 * @package MutovSlingr\Core
 */
abstract class Controller
{

    /**
     * @var \MutovSlingr\Views\ViewJson
     */
    protected $view;

    /**
     * Configurations
     */
    public function __construct()
    {
        $this->view = new \MutovSlingr\Views\ViewPhp();
//        $this->view = new \MutovSlingr\Views\ViewJson();
    }

    /**
     * @return \MutovSlingr\Views\ViewJson
     */
    public function getView()
    {
        return $this->view;
    }

}
