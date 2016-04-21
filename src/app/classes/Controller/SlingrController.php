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
use Ospinto\dBug;
use Slim\Interfaces\CollectionInterface;

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
    public function generateAction( array $args = array(), $request )
    {
        $templateFileName = 'test.json';

        if (is_array($args) && isset($args['template']) && is_string($args['template'])) {
            $templateFileName = $args['template'];
        }

        $template = $this->templateLoader->loadTemplate($templateFileName);
        $result = $this->processor->processTemplate($template);

        return $this->view->render( array( 'result' => $result ) );
    }


    public function onflyAction( array $args = array(), $request )
    {

        $jsonContent = $request->getParsedBody()['json_content'];
        $contents = json_decode($jsonContent, true);

        $result = $this->processor->processTemplate($contents);

        return $this->view->render( array( 'result' => $result ) );
    }

    /**
     * @param TemplateProcessor $processor
     */
    public function setProcessor($processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param CollectionInterface $config
     */
    public function setConfig(CollectionInterface $config)
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
