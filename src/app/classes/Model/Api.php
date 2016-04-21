<?php
/**
 * Created by PhpStorm.
 * User: hcorreia
 * Date: 21-04-2016
 * Time: 11:32
 */

namespace MutovSlingr\Model;

use GuzzleHttp\Client;
use Slim\Interfaces\CollectionInterface;

class Api
{

    /**
     * @var array
     */
    private $config;

    /**
     * Api constructor.
     * @param CollectionInterface $config
     */
    public function __construct(CollectionInterface $config)
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
            $this->config->get('api_host'),
          ['json' => json_decode($json, true)]
        );

        return $process_result->getBody()->getContents();
    }




}
