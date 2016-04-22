<?php


namespace MutovSlingr\Views;


interface ViewInterface
{

    /**
     * @return string
     */
    public function getContentType();

    /**
     * @param array $content
     * @return mixed
     */
    public function render(array $content);

    /**
     * @param array $headers
     */
    public function addHeaders(array $headers);


}
