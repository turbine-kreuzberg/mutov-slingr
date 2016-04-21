<?php
/**
 * Created by PhpStorm.
 * User: rozbeh.sharahi
 * Date: 21.04.2016
 * Time: 12:55
 */

namespace MutovSlingr\Controller;

use MutovSlingr\Core\Controller;
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
    private $processor;

    /**
     *
     * @param array $args
     * @return string
     */
    public function generateAction( array $args = array() )
    {
        return $this->view->render( array( 'test' => 'content' ) );
    }

    /**
     * @param TemplateProcessor $processor
     */
    public function setProcessor( $processor )
    {
        $this->processor = $processor;
    }

}