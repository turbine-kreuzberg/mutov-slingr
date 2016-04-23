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
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->pickerSettings = new PickerSettings($settings);
        $this->probabilityChecker = new ProbabilityChecker();

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
