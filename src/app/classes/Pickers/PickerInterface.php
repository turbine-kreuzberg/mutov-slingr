<?php


namespace MutovSlingr\Pickers;

interface PickerInterface
{

    /**
     * @param string $foreignObject
     * @param string $foreignField
     * @return array
     */
    public function pickValues($foreignObject, $foreignField);

}
