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
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        if (isset($settings['probability'])) {
            $this->probability = (int) $settings['probability'];
        }
    }

    /**
     * @param array $foreignObject
     * @param string $foreignField
     * @return array
     */
    public function pickValues(array $foreignObject, $foreignField)
    {
        if (is_int($this->probability) && mt_rand(1, 100) >= $this->probability) {
            return [];
        }

        if (count($foreignObject) <= 0) {
            return [];
        }

        $values = [];

        $key = array_rand($foreignObject, 1);
        $values[] = $foreignObject[$key][$foreignField];

        return $values;
    }

}
