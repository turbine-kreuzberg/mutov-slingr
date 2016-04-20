<?php

namespace MutovSlingr\Config;


use Slim\App;

class Router{

    /**
     * @param App $app
     */
    public function __construct(App $app)
    {
        $app->get('/{name}', 'MutovSlingr\Controller\HomeController:indexAction');
    }

}
