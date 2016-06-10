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

    /**
     * @var PickerSettings
     */
    private $pickerSettings;

    /**
     * @var ProbabilityChecker
     */
    private $probabilityChecker;

    /**
     * @param PickerSettings $settings
     * @param ProbabilityChecker $probabilityChecker
     */
    public function __construct(PickerSettings $settings, ProbabilityChecker $probabilityChecker)
    {
        $this->pickerSettings = $settings;
        $this->probabilityChecker = $probabilityChecker;
    }

    /**
     * @param array $foreignObject
     * @param string $foreignField
     * @return array
     */
    public function pickValues(array $foreignObject, $foreignField)
    {
        if (!$this->probabilityChecker->hit($this->pickerSettings->getProbability())) {
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
