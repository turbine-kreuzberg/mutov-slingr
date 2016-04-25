<?php


namespace MutovSlingr\Postprocessors;


class ConcatRecursivePostprocessor extends AbstractPostprocessor
{

    /**
     * @return array
     */
    protected function getDefaultProcessorSettings()
    {
        $defaultProcessorSettings = [
            'fieldId' => 'id',
            'fieldParentId' => 'parent_id',
            'fieldToConcat' => '',
            'concatenateChar'=> ','
        ];

        return $defaultProcessorSettings;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getProcessedData()
    {
        $objectType = $this->getObjectType();

        $concatFields = [];
        $fieldId = $this->processorSettings['fieldId'];
        $fieldParentId = $this->processorSettings['fieldParentId'];
        $fieldToConcat = $this->processorSettings['fieldToConcat'];

        foreach ($this->dataObjects[$objectType] as $key => $dataObject) {
            $fieldConcat = '';
            $id = $dataObject[$fieldId];
            $parentId = $dataObject[$fieldParentId];

            if (isset($concatFields[$parentId])) {
                $fieldConcat = $concatFields[$parentId] . $this->processorSettings['concatenateChar'];
            }

            $fieldConcat .= $dataObject[$fieldToConcat];
            $dataObject[$fieldToConcat] = $fieldConcat;

            if (!isset($concatFields[$id])) {
                $concatFields[$id] = $fieldConcat;
            }

            $this->dataObjects[$objectType][$key] = $dataObject;
        }

        return $this->dataObjects;
    }

}
