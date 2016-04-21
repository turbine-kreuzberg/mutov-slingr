<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 15:12
 */

namespace MutovSlingr\Pickers;


/**
 * Class RandomPicker
 * @package MutovSlingr\Pickers
 */
class RandomPicker
{

    protected $min = NULL;
    protected $max = NULL;
    protected $probability = NULL;


    /**
     * RandomPicker constructor.
     *
     */
    public function __construct($settings)
    {

        $this->min = $settings['min'];
        $this->max = $settings['max'];
        $this->probability = $settings['probability'];

    }


    public function pickValues()
    {




    }

}