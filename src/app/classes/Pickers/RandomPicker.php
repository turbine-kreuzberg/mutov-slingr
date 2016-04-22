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
class RandomPicker implements PickerInterface
{

    protected $min = null;
    protected $max = null;
    protected $probability = null;


    /**
     * RandomPicker constructor.
     *
     */
    public function __construct($settings)
    {
        $this->min = (int)$settings['min'];
        $this->max = (int)$settings['max'];
        $this->probability = $settings['probability'];
    }

    /**
     * @param string $foreignObject
     * @param string $foreignField
     * @return array
     */
    public function pickValues($foreignObject, $foreignField)
    {
        $columnList = array();

        if (rand(1, 100) > $this->probability) {
            return $columnList;
        }

        $list = array_rand($foreignObject, rand($this->min, $this->max));

        foreach ($list as $item) {
            $columnList[] = $foreignObject[$item][$foreignField];
        }

        return $columnList;
    }

}
