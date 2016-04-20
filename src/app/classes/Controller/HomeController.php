<?php

namespace MutovSlingr\Controller;


use Slim\Http\Request;
use Slim\Http\Response;

class HomeController
{

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return string
     */
    public function indexAction(Request $request, Response $response, $args)
    {
        return '> You suck '.$args['name'].'! #NOT';
    }
    
}
