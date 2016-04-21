<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 11:32
 */

namespace MutovSlingr\Model;


class Api
{

    const _API_END_POINT = 'http://api.mutov-slingr.votum-local.de/api/v1/data';

    /**
     * @param $json
     * @return mixed
     */
    public function apiCall($json)
    {
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, self::_API_END_POINT);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);

        return $result;
    }




}