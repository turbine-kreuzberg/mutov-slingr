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

    /**
     * @var array
     */
    private $config;

    /**
     * Api constructor.
     * @param array $config
     */
    public function __construct($config)
    {

        $this->config = $config;
    }

    /**
     * @param string $json
     * @return mixed
     */
    public function apiCall($json)
    {
        $client = new Client();
        $process_result = $client->post(
            $this->config['api']['host'],
          ['json' => json_decode($json, true)]
        );

        return $process_result->getBody()->getContents();
    }




}