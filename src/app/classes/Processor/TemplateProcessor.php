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


    const _TEMPLATES_FOLDER = '/var/www/mutov-slingr/app/var'; // NEED to be changed

    protected $flatData = NULL;


    protected function getTemplate($templateFileName)
    {

        $fullPath = self::_TEMPLATES_FOLDER.'/'.$templateFileName;
        $contents = file_get_contents($fullPath);

        $contents = json_decode($contents, true);

        return $contents;

    }

    protected function generateFlatData($templatesContent)
    {

        $apiCall = new Api();

        $entitiesList = array();
        foreach($templatesContent as $template){

            $label = $template['label'];
            $definition = json_encode($template['definition']);
            $entitiesList[$label] = json_decode($apiCall->apiCall($definition),true);
        }

        return $entitiesList;

    }



    public function processTemplate($templateFileName)
    {

        $templateContent = $this->getTemplate($templateFileName);

        echo('<h1>Template content</h1>');
        new dbug($templateContent);

        //exit;

        $this->flatData = $this->generateFlatData($templateContent['templates']);

        echo('<h1>Original data</h1>');
        new dBug($this->flatData);



        foreach($templateContent['relations'] as $tableTo=>$relation)
        {

            //new dBug($tableTo);
            //new dBug($relation);

            foreach($relation as  $columnTo=>$relationData){

                //new dBug($columnTo);
                //new dBug($relationData);

                $foreignTable = $relationData['foreignTable'];
                $foreignField = $relationData['foreignField'];
                $pickerSettings = $relationData['pickerSettings'];
                $quantity = $pickerSettings['max'];
                //$pickerClass = 'MutovSlingr\\Pickers\\'.ucfirst($pickerSettings['type']).'Picker';


                //$picker = new $pickerClass($pickerSettings);
                //$picker = new RandomPicker($pickerSettings);

                //new dbug($picker); exit;

                $this->addElement($tableTo, $columnTo, $foreignTable, $foreignField, 'random', $quantity);



            }

            echo('<h1>Result array</h1>');
            new dbug($this->flatData);




        }


        // process relations



    }


    protected function addElement($tableTo, $columnTo, $foreignTable, $foreignField, $pickType, $quantity)
    {

        foreach($this->flatData[$tableTo] as $idx=>$item){

            $values = $this->pickValues($foreignTable, $foreignField, $pickType, $quantity);

            $this->flatData[$tableTo][$idx][$columnTo] = implode(',',$values);

        }




    }



    protected function pickValues($foreignTable, $foreignField, $pickType = 'random', $quantity)
    {
        $columnList = array();
        $list = array_rand($this->flatData[$foreignTable], $quantity);

        foreach($list as $item){
            $columnList[] = $this->flatData[$foreignTable][$item][$foreignField];
        }

        return $columnList;
    }





}