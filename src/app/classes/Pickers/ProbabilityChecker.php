<?php
/**
 * Copyright notice
 *
 *
 * @author: Christian Müllenhagen <christian.muellenhagen@votum.de> - VOTUM GmbH
 * All rights reserved
 * @date: 23.04.16
 */

namespace MutovSlingr\Pickers;


class ProbabilityChecker
{
    const MINIMUM = 1;
    const MAXIMUM = 100;

    /**
     * @param int $probability
     * @return bool
     */
    public function hit($probability)
    {
        return
            !is_int($probability)
            || mt_rand(self::MINIMUM, self::MAXIMUM) < $probability;
    }
}
