<?php


namespace MutovSlingr\Postprocessors;


abstract class AbstractPostprocessor implements PostprocessorInterface
{

    /** @var array */
    protected $dataObjects;

    /** @var array */
    protected $processorSettings;

    /**
     * ConcatRecursivePostprocessor constructor.
     * @param array $dataObjects
     * @param array $processorSettings
     */
    public function __construct(array $dataObjects, array $processorSettings)
    {
        $this->dataObjects = $dataObjects;
        $this->setProcessorSettings($processorSettings);
    }

    /**
     * @param array $processorSettings
     */
    protected function setProcessorSettings(array $processorSettings)
    {
        $defaultProcessorSettings = $this->getDefaultProcessorSettings();

        $processorSettings = array_merge($defaultProcessorSettings, $processorSettings);

        $this->processorSettings = $processorSettings;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getObjectType()
    {
        $objectType = $this->processorSettings['object'];

        if (!isset($this->dataObjects[$objectType])) {
            throw new \Exception('Unknown object type (' . $objectType . ')');
        }

        return $objectType;
    }

}
