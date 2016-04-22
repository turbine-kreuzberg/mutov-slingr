<?php
/**
 * Copyright notice
 *
 *
 * @author: Christian Müllenhagen <christian.muellenhagen@votum.de> - VOTUM GmbH
 * All rights reserved
 * @date: 21.04.16
 */

namespace MutovSlingr\Views;

abstract class View implements ViewInterface
{
    const CONTENT_TYPE = 'text/plain';

    /**
     * The content type
     *
     * @return string
     */
    public function getContentType()
    {
        return $this::CONTENT_TYPE;
    }
}
