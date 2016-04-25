<?php


namespace MutovSlingr\Postprocessors;


class RemoveFieldsPostprocessor extends AbstractPostprocessor
{

    /**
     * @return array
     */
    protected function getDefaultProcessorSettings()
    {
        $defaultProcessorSettings = [
            'fieldsToRemove' => [],
        ];

        return $defaultProcessorSettings;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getProcessedData()
    {
        $objectType = $this->processorSettings['object'];

        if (!isset($this->dataObjects[$objectType])) {
             throw new \Exception('Unknown object type (' . $objectType . ')');
        }

        $fieldsToRemove = array_flip($this->processorSettings['fieldsToRemove']);

        foreach ($this->dataObjects[$objectType] as $key => $dataObject) {
            $this->dataObjects[$objectType][$key] = array_diff_key($dataObject, $fieldsToRemove);
        }

        return $this->dataObjects;
    }

}
