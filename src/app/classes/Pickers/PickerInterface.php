<?php


namespace MutovSlingr\Pickers;

interface PickerInterface
{

    /**
     * @param array $foreignObject
     * @param string $foreignField
     * @return array
     */
    public function pickValues(array $foreignObject, $foreignField);

}
