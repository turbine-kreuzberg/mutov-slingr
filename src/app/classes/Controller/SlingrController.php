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

/**
 * Class SlingrController
 *
 * The main controller
 *
 * @package MutovSlingr\Controller
 */
class SlingrController extends Controller
{

    /**
     * @var TemplateProcessor
     */
    protected $processor;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var TemplateLoader
     */
    private $templateLoader;

    /**
     *
     * @param array $args
     * @return string
     */
    public function generateAction( array $args = array() )
    {

        $template = $this->templateLoader->loadTemplate('test.json');
        $result = $this->processor->processTemplate($template);

        return $this->view->render( array( 'result' => $result ) );
    }

    /**
     * @param TemplateProcessor $processor
     */
    public function setProcessor( $processor )
    {
        $this->processor = $processor;
    }

    /**
     * @param array $config
     */
    public function setConfig( $config )
    {
        $this->config = $config;
    }

    /**
     * @param TemplateLoader $templateLoader
     */
    public function setTemplateLoader(TemplateLoader $templateLoader)
    {
        $this->templateLoader = $templateLoader;
    }

}