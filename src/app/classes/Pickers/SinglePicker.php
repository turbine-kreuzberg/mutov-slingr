<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 15:12
 */

namespace MutovSlingr\Pickers;


/**
 * Class RandomPicker
 * @package MutovSlingr\Pickers
 */
class SinglePicker
{



    /**
     * RandomPicker constructor.
     *
     */
    public function __construct($settings)
    {



    }


    public function pickValues($foreignTable, $foreignField)
    {
        $columnList = array();

        $key = array_rand($foreignTable, 1);
        $columnList[] = $foreignTable[$key][$foreignField];
        return $columnList;
    }

}