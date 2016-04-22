<?php


namespace MutovSlingr\Controller;


interface ControllerInterface
{

    /**
     * @param \MutovSlingr\Views\ViewInterface[] $views
     */
    public function setViews(array $views);

}
