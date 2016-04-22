<?php

namespace MutovSlingr\Views;

/**
 * Class ViewHtml
 *
 * @package MutovSlingr\Views
 */
class ViewHtml extends View
{

    const CONTENT_TYPE = 'text/html';

    /** @var string */
    protected $themeFolder = null;

    /** @var string */
    protected $themeFile;

    /**
     * @param string $themeFile
     */
    public function setThemeFile($themeFile)
    {
        $this->themeFile = $themeFile;
    }

    /**
     * @param array $data
     * @return string
     */
    public function render(array $data)
    {
        ob_start();

        include $this->themeFolder . '/' . $this->themeFile;

        $content = ob_get_clean();

        return $content;
    }

    /**
     * @param string $themeFolder
     */
    public function setLayout($themeFolder)
    {
        $this->themeFolder = $themeFolder;
    }

}
