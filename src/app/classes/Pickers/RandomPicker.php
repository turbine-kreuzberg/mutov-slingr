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
class RandomPicker
{

    protected $min = NULL;
    protected $max = NULL;
    protected $probability = NULL;


    /**
     * RandomPicker constructor.
     *
     */
    public function __construct($settings)
    {

        $this->min = (int)$settings['min'];
        $this->max = (int)$settings['max'];
        $this->probability = $settings['probability'];

    }


    public function pickValues($foreignTable, $foreignField)
    {

        $columnList = array();

        if(rand(1,100) > $this->probability) return $columnList;

        $list = array_rand($foreignTable, rand($this->min,$this->max));

        foreach($list as $item){
            $columnList[] = $foreignTable[$item][$foreignField];
        }

        return $columnList;

    }

}