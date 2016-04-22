<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 11:13
 */

namespace MutovSlingr\Processor;
use MutovSlingr\Model\Api;
use Ospinto\dBug;
use Slim\Interfaces\CollectionInterface;


class TemplateProcessor
{

    protected $flatData = NULL;

    /**
     * @var Api $api
     */
    protected $api = NULL;

    /**
     * @var CollectionInterface
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
     * @param string $templatesContent
     *
     * @return array
     */
    protected function generateFlatData($templatesContent)
    {
        $entitiesList = array();

        foreach($templatesContent as $template){

            $label = $template['label'];
            $templateDefinition = $template['definition'];

            $templateDefinition = array_merge($templateDefinition, $this->config->get('data_generator_export_configuration'));

            $definition = json_encode($templateDefinition);

            $entitiesList[$label] = json_decode($this->api->apiCall($definition),true);
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

        foreach($template['relations'] as $tableTo=>$relation)
        {

            foreach($relation as  $columnTo=>$relationData){

                $foreignTable = $relationData['foreignTable'];
                $foreignField = $relationData['foreignField'];
                $pickerSettings = $relationData['pickerSettings'];
                $pickerClass = 'MutovSlingr\\Pickers\\'.ucfirst($pickerSettings['type']).'Picker';

                $pickerInstance = new $pickerClass($pickerSettings);

                $this->addElement($tableTo, $columnTo, $foreignTable, $foreignField, $pickerInstance);

            }

        }

        return $this->flatData;
    }


    protected function addElement($tableTo, $columnTo, $foreignTable, $foreignField, $pickerInstance)
    {

        foreach($this->flatData[$tableTo] as $idx=>$item){

            $values = $pickerInstance->pickValues($this->flatData[$foreignTable], $foreignField);

            $this->flatData[$tableTo][$idx][$columnTo] = implode(',',$values);

        }




    }



}
