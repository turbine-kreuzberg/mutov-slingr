<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 11:32
 */

namespace MutovSlingr\Model;

use GuzzleHttp\Client;

class Api
{

    const _API_END_POINT = 'http://api.mutov-slingr.votum-local.de/api/v1/data';

    /**
     * @param string $json
     * @return mixed
     */
    public function apiCall($json)
    {
        print_r($json);
        $client = new Client();
        $process_result = $client->post(
          self::_API_END_POINT,
          ['json' => json_decode($json, true)]
        );

        return $process_result->getBody()->getContents();
    }




}