<?php
/**
 * Created by PhpStorm.
 * User: rozbeh.sharahi
 * Date: 21.04.2016
 * Time: 12:55
 */

namespace MutovSlingr\Controller;

use MutovSlingr\Controller\AbstractController;
use MutovSlingr\Loader\TemplateLoader;
use MutovSlingr\Processor\TemplateProcessor;
use Slim\Interfaces\CollectionInterface;

/**
 * Class SlingrController
 *
 * The main controller
 *
 * @package MutovSlingr\Controller
 */
class SlingrController extends AbstractController
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
    public function generateAction(array $args = array())
    {
        $templateFileName = 'test.json';
        $outputFormat = 'json';

        if (is_array($args)) {
            if (isset($args['template']) && is_string($args['template'])) {
                $templateFileName = $args['template'] . '.json';
            }

            if (isset($args['outputFormat']) && is_string($args['outputFormat'])) {
                $outputFormat = $args['outputFormat'];
            }
        }

        $template = $this->templateLoader->loadTemplate($templateFileName);
        $result = $this->processor->processTemplate($template);

        return $this->getView($outputFormat)->render(array('result' => $result));
    }

    public function onflyAction(array $args = array(), $request)
    {
        $outputFormat = 'json';

        if (isset($args['outputFormat']) && is_string($args['outputFormat'])) {
            $outputFormat = $args['outputFormat'];
        }

        $jsonContent = $request->getParsedBody()['json_content'];
        $contents = json_decode($jsonContent, true);

        $result = $this->processor->processTemplate($contents);

        return $this->getView($outputFormat)->render(array('result' => $result));
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

    public function getDownloadHeadersForFile( $file )
    {
        return array(
          'Content-Description'       => 'File Transfer',
          'Content-Type'              => 'application/octet-stream',
          'Content-Disposition'       => 'attachment; filename='.$file,
          'Content-Transfer-Encoding' => 'binary',
          'Connection'                => 'Keep-Alive',
          'Expires'                   => '0',
          'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
          'Pragma'                    => 'public'
        );
    }

}
