<?php

namespace MutovSlingr\Views;

/**
 * Class ViewPhp
 *
 * @package MutovSlingr\Views
 */
class ViewPhp extends View
{
    /**
     * @param array $content
     * @return string
     */
    public function render( array $content )
    {
        return '<?php $data = ' . var_export($content, true) . ';';
    }

    /**
     * The content type
     *
     * @return string
     */
    public function getContentType()
    {
        return self::CONTENT_TYPE_TEXT_PLAIN;
    }
}
