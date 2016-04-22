<?php

namespace MutovSlingr\Views;

/**
 * Class ViewJson
 *
 * @package MutovSlingr\Views
 */
class ViewJson extends View
{
    const CONTENT_TYPE = 'application/json';

    /**
     * @param array $content
     * @return string
     */
    public function render(array $content)
    {
        return json_encode($content, JSON_PRETTY_PRINT);
    }

}
