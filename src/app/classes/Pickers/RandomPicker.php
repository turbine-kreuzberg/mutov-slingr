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

    /**
     * @var PickerSettings
     */
    private $pickerSettings;

    /**
     * @var ProbabilityChecker
     */
    private $probabilityChecker;

    /**
     * RandomPicker constructor.
     * @param $settings
     */
    public function __construct($settings)
    {
        $this->pickerSettings = new PickerSettings($settings);
        $this->probabilityChecker = new ProbabilityChecker();
    }

    /**
     * @param array $foreignObject
     * @param string $foreignField
     * @return array
     * @throws \Exception
     */
    public function pickValues(array $foreignObject, $foreignField)
    {
        if (!$this->probabilityChecker->hit($this->pickerSettings->getProbability())) {
            return [];
        }

        $min = $this->pickerSettings->getMin();
        $max = $this->pickerSettings->getMax();

        $countObjects = count($foreignObject);

        if ($min >= $countObjects) {
            $min = $countObjects;
        }

        if ($max >= $countObjects) {
            $max = $countObjects;
        }

        $list = array_rand($foreignObject, mt_rand($min, $max));

        if (is_scalar($list)) {
            $list = [$list];
        }

        $values = [];

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
        if (is_null($this->pickerSettings->getSeparator())) {
            return $values;
        }

        return implode($this->pickerSettings->getSeparator(), $values);
    }

}
