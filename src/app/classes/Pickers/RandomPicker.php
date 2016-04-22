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
    protected $probability;
    protected $separator = null;

    /**
     * RandomPicker constructor.
     * @param $settings
     */
    public function __construct($settings)
    {
        $this->min = (int)$settings['min'];
        $this->max = (int)$settings['max'];

        if (isset($settings['probability'])) {
            $this->probability = (int) $settings['probability'];
        }

        if (isset($settings['separator'])) {
            $this->separator = $settings['separator'];
        }
    }

    /**
     * @param string $foreignObject
     * @param string $foreignField
     * @return array
     * @throws \Exception
     */
    public function pickValues($foreignObject, $foreignField)
    {
        $values = array();

        if (is_int($this->probability) && mt_rand(1, 100) >= $this->probability) {
            return $values;
        }

        $min = $this->min;
        $max = $this->max;

        if ($min > $max) {
            throw new \Exception('Minimum is greater than maximum.');
        }

        $countObjects = count($foreignObject);

        if ($min >= $countObjects) {
            $min = $countObjects;
        }

        if ($max >= $countObjects) {
            $max = $countObjects;
        }

        $list = array_rand($foreignObject, mt_rand($min, $max));

        if (is_scalar($list)) {
            $list = array($list);
        }

        foreach ($list as $item) {
            $values[] = $foreignObject[$item][$foreignField];
        }

        return $this->formatPickedValues($values);
    }

    /**
     * @param array $values
     * @return array|string
     */
    private function formatPickedValues(array $values)
    {
        if (is_null($this->separator)) {
            return $values;
        }

        return implode($this->separator, $values);
    }

}
