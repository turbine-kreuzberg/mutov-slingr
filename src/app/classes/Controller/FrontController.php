<?php
/**
 * Created by PhpStorm.
 * User: rozbeh.sharahi
 * Date: 21.04.2016
 * Time: 12:55
 */

namespace MutovSlingr\Controller;

use MutovSlingr\Core\Controller;
use MutovSlingr\Loader\TemplateLoader;
use MutovSlingr\Processor\TemplateProcessor;
use Slim\Interfaces\CollectionInterface;

/**
 * Class FrontController
 *
 * The main controller
 *
 * @package MutovSlingr\Controller
 */
class FrontController extends Controller
{



    /**
     * @var array
     */
    protected $config;

    /**
     * Configurations
     */
    public function __construct()
    {
        $this->view = new \MutovSlingr\Views\ViewHtml();

        $this->view->setLayout('/var/www/mutov-slingr/app/themes');
    }


    /**
     *
     * @param array $args
     * @return string
     */
    public function indexAction( array $args = array())
    {
        return $this->view->render('base.phtml',
            array( 'result' => 'hello world' ));
    }







    /**
     * @param CollectionInterface $config
     */
    public function setConfig(CollectionInterface $config )
    {
        $this->config = $config;
    }



}
