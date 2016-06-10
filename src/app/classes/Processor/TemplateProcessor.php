<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 11:13
 */

namespace MutovSlingr\Processor;

use MutovSlingr\Model\Api;
use MutovSlingr\Pickers\PickerFactory;
use MutovSlingr\Pickers\PickerInterface;
use MutovSlingr\Postprocessors\ConcatRecursivePostprocessor;
use MutovSlingr\Postprocessors\RemoveFieldsPostprocessor;
use Slim\Interfaces\CollectionInterface;

class TemplateProcessor
{

    const TYPE_TEMPLATES = 'templates';
    const TYPE_RELATIONS = 'relations';
    const TYPE_POSTPROCESSORS = 'postprocessors';

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
     * @var PickerFactory
     */
    private $pickerFactory;

    /**
     * TemplateProcessor constructor.
     * @param Api $api
     * @param CollectionInterface $config
     * @param PickerFactory $pickerFactory
     */
    public function __construct(
        Api $api,
        CollectionInterface $config,
        PickerFactory $pickerFactory
    )
    {
        $this->config = $config;
        $this->api = $api;
        $this->pickerFactory = $pickerFactory;
    }

    /**
     * @param array $templatesContent
     *
     * @return array
     */
    protected function generateFlatData(array $templatesContent)
    {
        $entitiesList = array();

        foreach ($templatesContent as $template) {
            //TODO: add validation or better schema validation
            $label = $template['label'];
            $templateDefinition = $template['definition'];

            $templateDefinition = array_merge(
                $templateDefinition,
                $this->config->get('data_generator_export_configuration')
            );

            $definition = json_encode($templateDefinition);

            $apiResult = $this->api->apiCall($definition);
            $entitiesList[$label] = json_decode($apiResult, true);
        }

        return $entitiesList;
    }

    /**
     * @param array $template
     *
     * @return array
     * @throws \ErrorException
     */
    public function processTemplate(array $template)
    {
        if (!isset($template[self::TYPE_TEMPLATES])) {
            throw new \ErrorException('No templates to process.');
        }

        foreach ($template as $type => $settings) {

            switch ($type) {
                case self::TYPE_TEMPLATES:
                    $this->flatData = $this->generateFlatData($settings);
                    break;

                case self::TYPE_POSTPROCESSORS:
                    $this->processPostprocessors($settings);
                    break;

                case self::TYPE_RELATIONS:
                    if (is_array($settings)) {
                        $this->processRelations($settings);
                    }
                    break;

                default:
                    /** @todo probably add error handling/logging for unknown/invalid type */
                    break;
            }
        }

        return $this->flatData;
    }

    /**
     * @param string $tableTo
     * @param string $columnTo
     * @param string $foreignObject
     * @param string $foreignField
     * @param PickerInterface $picker
     */
    protected function addElement($tableTo, $columnTo, $foreignObject, $foreignField, PickerInterface $picker)
    {
        foreach ($this->flatData[$tableTo] as $idx => $item) {
            $values = $picker->pickValues($this->flatData[$foreignObject], $foreignField);
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
                $picker = $this->pickerFactory->createPicker($pickerSettings);

                $this->addElement($tableTo, $columnTo, $foreignObject, $foreignField, $picker);
            }
        }
    }

    /**
     * @param array $postprocessors
     * @return array
     * @throws \Exception
     */
    private function processPostprocessors(array $postprocessors)
    {
        $entitiesList = array();

        foreach ($postprocessors as $postprocessorSettings) {
            $postprocessor = null;

            /** @todo add automatic registration and determining for postporcessor plugins  */
            switch ($postprocessorSettings['type']) {
                case 'concat_recursive':
                    $postprocessor = new ConcatRecursivePostprocessor($this->flatData, $postprocessorSettings);
                    break;

                case 'remove_fields':
                    $postprocessor = new RemoveFieldsPostprocessor($this->flatData, $postprocessorSettings);
                    break;

                default:
                    throw new \Exception('Invalid postprocessor (' . $postprocessorSettings['type'] . ')');
                    break;
            }

            if (!is_null($postprocessor)) {
                $this->flatData = $postprocessor->getProcessedData();
            }
        }

        return $entitiesList;
    }

}
