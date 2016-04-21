<?php

namespace MutovSlingr\Views;

/**
 * Class ViewHtml
 *
 * @package MutovSlingr\Views
 */
class ViewHtml extends View
{

    protected $themeFolder = NULL;

    /**
     * @param array $content
     * @return string
     */
    public function render($themeFile, array $data )
    {

        ob_start();

        include($this->themeFolder.'/'.$themeFile);

        $content = ob_get_clean();
        return $content;
    }






    /**
     * The content type
     *
     * @return string
     */
    public function getContentType()
    {
        return self::CONTENT_TYPE_TEXT_HTML;
    }


    public function setLayout($themeFolder)
    {
        $this->themeFolder = $themeFolder;

    }


}
