<?php

namespace MutovSlingr\Controller;

/**
 * Class Controller
 *
 * Base controller abstract for all controllers
 *
 * @package MutovSlingr\Core
 */
abstract class AbstractController implements ControllerInterface
{

    /**
     * @var \MutovSlingr\Views\ViewInterface
     */
    protected $view;

    /** @var \MutovSlingr\Views\ViewInterface[] */
    protected $views;

    /**
     * @param string $type
     * @return \MutovSlingr\Views\ViewInterface
     * @throws \Exception
     */
    public function getView($type = 'json')
    {
        if (is_null($this->view)) {
            if (!is_array($this->views)) {
                $this->view = new \MutovSlingr\Views\ViewPlain();
//                throw new \Exception('No views available.');
            }
            else if (count($this->views) === 1) {
                $this->view = reset($this->views);
            }
            else if (isset($this->views[$type])) {
                $this->view = $this->views[$type];
            }
            else {
                $this->view = reset($this->views);
            }
        }

        return $this->view;
    }

    /**
     * @param \MutovSlingr\Views\ViewInterface[] $views
     */
    public function setViews(array $views)
    {
        $this->views = $views;
    }

}
