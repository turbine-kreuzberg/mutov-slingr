<?php

namespace MutovSlingr\Views;

/**
 * Class ViewJson
 *
 * @package MutovSlingr\Views
 */
class ViewJson
{
    /**
     * @param array|string $content
     * @return string
     */
    public function render( array $content )
    {
        return json_encode( $content, JSON_PRETTY_PRINT );
    }

    /**
     * The content type
     *
     * @return string
     */
    public function getContentType()
    {
        return 'application/json';
    }
}