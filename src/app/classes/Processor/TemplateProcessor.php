<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 11:13
 */

namespace MutovSlingr\Processor;

use MutovSlingr\Model\Api;
use MutovSlingr\Pickers\PickerInterface;
use Slim\Interfaces\CollectionInterface;

class TemplateProcessor
{

    protected $flatData = null;

    /**
     * @var Api $api
     */
    protected $api = null;

    /**
     * @var CollectionInterface $config
     */
    private $config;

    /**
     * TemplateProcessor constructor.
     * @param Api $api
     * @param CollectionInterface $config
     */
    public function __construct(Api $api, CollectionInterface $config)
    {
        $this->config = $config;
        $this->api = $api;
    }

    /**
     * @param array $templatesContent
     *
     * @return array
     */
    protected function generateFlatData($templatesContent)
    {
        $entitiesList = array();

        if (is_array($templatesContent)) {
            foreach ($templatesContent as $template) {
                $label = $template['label'];
                $templateDefinition = $template['definition'];

                $templateDefinition = array_merge($templateDefinition,
                    $this->config->get('data_generator_export_configuration'));

                $definition = json_encode($templateDefinition);

                $entitiesList[$label] = json_decode($this->api->apiCall($definition), true);
            }
        }

        return $entitiesList;
    }

    /**
     * @param string $template
     * @return array|null
     */
    public function processTemplate($template)
    {
        $this->flatData = $this->generateFlatData($template['templates']);

        if (isset($template['relations']) && is_array($template['relations'])) {
            $this->processRelations($template['relations']);
        }

        return $this->flatData;
    }

    /**
     * @param $tableTo
     * @param $columnTo
     * @param $foreignObject
     * @param $foreignField
     * @param PickerInterface $pickerInstance
     */
    protected function addElement($tableTo, $columnTo, $foreignObject, $foreignField, PickerInterface $pickerInstance)
    {
        foreach ($this->flatData[$tableTo] as $idx => $item) {
            $values = $pickerInstance->pickValues($this->flatData[$foreignObject], $foreignField);
            $this->flatData[$tableTo][$idx][$columnTo] = $values;
        }
    }

    /**
     * @param array $relations
     */
    private function processRelations(array $relations)
    {
        foreach ($relations as $tableTo => $relation) {
            foreach ($relation as $columnTo => $relationData) {
                $foreignObject = $relationData['foreignObject'];
                $foreignField = $relationData['foreignField'];
                $pickerSettings = $relationData['pickerSettings'];
                $pickerClass = 'MutovSlingr\\Pickers\\' . ucfirst($pickerSettings['type']) . 'Picker';

                $pickerInstance = new $pickerClass($pickerSettings);

                $this->addElement($tableTo, $columnTo, $foreignObject, $foreignField, $pickerInstance);
            }
        }
    }

}
