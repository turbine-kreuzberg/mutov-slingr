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


class TemplateProcessor
{


    const _TEMPLATES_FOLDER = '/var/www/mutov-slingr/app/var'; // NEED to be changed


    public function processTemplate($templateFileName)
    {

        $apiCall = new Api();

        $fullPath = self::_TEMPLATES_FOLDER.'/'.$templateFileName;
        $contents = file_get_contents($fullPath);

        $contents = json_decode($contents, true);
        new dbug($contents);

        $entitiesList = array();
        foreach($contents['templates'] as $template){

            $label = $template['label'];
            $definition = json_encode($template['definition']);
            $entitiesList[$label] = json_decode($apiCall->apiCall($definition),true);
        }

        new dBug($entitiesList);

        foreach($contents['relations'] as $relation)
        {
            $field = $relation['field'];
            $entity = $relation['entity'];
            $qnt = $relation['qnt'];

            list($tableTo,$columnTo) = $this->splitField($field);
            list($tableFrom,$columnFrom) = $this->splitField($entity);

            $values = $this->pickValues($entitiesList[$tableFrom], $columnFrom, $qnt);

            new dbug($values);


        }


        // process relations



    }



    private function pickValues($tableFrom, $columnFrom, $qnt)
    {

        $columnList = array();
        $list = array_rand($tableFrom, $qnt);

        foreach($list as $item){
            $columnList[] = $tableFrom[$item][$columnFrom];


        }

        return $columnList;

    }


    private function splitField($field)
    {

        $parts = explode('.',$field);

        return array(trim($parts[0]),trim($parts[1]));


    }



}