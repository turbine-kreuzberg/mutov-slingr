<?php

namespace MutovSlingr\Views;

use Ospinto\dBug;

/**
 * Class ViewXml
 *
 * @package MutovSlingr\Views
 */
class ViewXml extends View
{
    const CONTENT_TYPE = 'application/xml';

    /**
     * @param array $content
     * @return string
     */
    public function render(array $content)
    {

        $xml = new \SimpleXMLElement('<root/>');
        $this->arrayToXml($content, $xml);

        return $xml->asXML();
    }


    /**
     * Convert an array to XML
     * @param array $array
     * @param SimpleXMLElement $xml
     */
    protected function arrayToXml($array, &$xml){
        foreach ($array as $key => $value) {
            if(is_array($value)){

                if(is_int($key)){
                    $key = "element";
                }
                $label = $xml->addChild($key);
                $this->arrayToXml($value, $label);
            }
            else {
                if(is_int($key)){
                    $key = "el";
                }
                $xml->addChild($key, $value);
            }
        }
    }

}
