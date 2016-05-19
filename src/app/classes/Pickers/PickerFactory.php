<?php

namespace MutovSlingr\Pickers;


class PickerFactory
{

    /**
     * @param array $pickerSettings
     *
     * @return PickerInterface
     * @throws \ErrorException
     */
    public function createPicker(array $pickerSettings)
    {
        if (!isset($pickerSettings['type'])) {
            throw new \ErrorException('Missing picker type.');
        }

        $pickerClass = 'MutovSlingr\\Pickers\\' . ucfirst($pickerSettings['type']) . 'Picker';

        if (!class_exists($pickerClass)) {
            throw new \ErrorException(sprintf('Class "%s" does not exist.', $pickerClass));
        }

        $pickerSettings = new PickerSettings($pickerSettings);

        $probabilityChecker = new ProbabilityChecker();

        return new $pickerClass($pickerSettings, $probabilityChecker);
    }

}
