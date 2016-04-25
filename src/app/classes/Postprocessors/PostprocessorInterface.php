<?php


namespace MutovSlingr\Postprocessors;


interface PostprocessorInterface
{

    /**
     * PostprocessorInterface constructor.
     * @param array $dataObjects
     * @param array $processorSettings
     */
    public function __construct(array $dataObjects, array $processorSettings);

    /**
     * @return mixed
     */
    public function getProcessedData();

}
