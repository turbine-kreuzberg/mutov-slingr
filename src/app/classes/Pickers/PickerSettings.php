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


class PickerSettings
{

    /**
     * @var int
     */
    private $probability;

    /**
     * @var int
     */
    private $min;

    /**
     * @var int
     */
    private $max;

    /**
     * @var string
     */
    private $separator;

    /**
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        if (isset($settings['probability'])) {
            $this->probability = (int) $settings['probability'];
        }

        if (isset($settings['min'])) {
            $this->min = (int)$settings['min'];
        }

        if (isset($settings['max'])) {
            $this->max = (int)$settings['max'];
        }

        if ($this->min > $this->max) {
            throw new \LogicException('Minimum is greater than maximum.');
        }

        if (isset($settings['separator'])) {
            $this->separator = $settings['separator'];
        }
    }

    /**
     * @return int
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }
}
