<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 11:13
 */

namespace MutovSlingr\Processor;
use MutovSlingr\Model\Api;
use MutovSlingr\Pickers\RandomPicker;
use Ospinto\dBug;


class TemplateProcessor
{

    protected $flatData = NULL;

    /**
     * @var Api $api
     */
    protected $api = NULL;

    /**
     * TemplateProcessor constructor.
     * @param Api $api
     */
    public function __construct(Api $api)
    {
       $this->api = $api;
    }




    protected function generateFlatData($templatesContent)
    {
        $entitiesList = array();
        foreach($templatesContent as $template){

            $label = $template['label'];
            $definition = json_encode($template['definition']);
            $entitiesList[$label] = json_decode($this->api->apiCall($definition),true);
        }

        return $entitiesList;

    }



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