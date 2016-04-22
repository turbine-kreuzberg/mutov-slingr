<?php


namespace MutovSlingr\Views;

abstract class View implements ViewInterface
{
    const CONTENT_TYPE = 'text/plain';

    /**
     * @var array
     */
    protected $headers = array(
      'Content-Type' => self::CONTENT_TYPE
    );

    /**
     * @param array $headers
     */
    public function addHeaders( $headers )
    {
        $this->headers = array_replace_recursive( $this->headers, $headers );
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    /**
     * The content type
     * 
     * @deprecated
     * @return string
     */
    public function getContentType()
    {
        return $this::CONTENT_TYPE;
    }
}
