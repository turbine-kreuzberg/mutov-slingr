<?php
/**
 * Created by PhpStorm.
 * User: rozbeh.sharahi
 * Date: 21.04.2016
 * Time: 13:11
 */

namespace MutovSlingr\Controller;

use MutovSlingr\Core\Controller;

/**
 * Class ErrorController
 *
 * Our error controller
 *
 * @package MutovSlingr\Controller
 */
class ErrorController extends Controller
{
    /**
     * Error handling
     *
     * @param \Exception $exception
     * @return string
     */
    public function errorAction( \Exception $exception )
    {
        return $this->view->render(
          array(
            'error' => $exception->getMessage(),
            'trace' => $exception->getTrace(),
          )
        );
    }

    /**
     * Page not found handling
     *
     * @return string
     */
    public function notFoundAction()
    {
        return $this->view->render( array( 'error' => 'Page not found!' ) );
    }

}