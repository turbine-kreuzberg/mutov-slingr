<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 15:12
 */

namespace MutovSlingr\Pickers;


/**
 * Class SinglePicker
 * @package MutovSlingr\Pickers
 */
class SinglePicker implements PickerInterface
{

    protected $probability;

    /**
     * SinglePicker constructor.
     *
     */
    public function __construct($settings)
    {
        if (isset($settings['probability'])) {
            $this->probability = (int) $settings['probability'];
        }
    }

    /**
     * @param string $foreignObject
     * @param string $foreignField
     * @return array
     */
    public function pickValues($foreignObject, $foreignField)
    {
        $values = array();

        if (is_int($this->probability) && mt_rand(1, 100) >= $this->probability) {
            return $values;
        }

        $key = array_rand($foreignObject, 1);
        $values[] = $foreignObject[$key][$foreignField];

        return $values;
    }

}
