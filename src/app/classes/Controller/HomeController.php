<?php

namespace MutovSlingr\Controller;
use MutovSlingr\Processor\TemplateProcessor;
use MutovSlingr\Model\Api;
use Ospinto\dBug;

use Slim\Http\Request;
use Slim\Http\Response;


class HomeController
{




    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return string
     */
    public function indexAction(Request $request, Response $response, $args)
    {
        return 'There no place like /home';
//        return '> You suck '.$args['name'].'! #NOT';
    }


    /**
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function testAction($request, $response, $args)
    {

        $testData = '{
            "numRows": 10,
            "rows": [
                        {
                        "type": "AlphaNumeric",
            "title": "Random Password",
            "settings": {
                "placeholder": "LLLxxLLLxLL"
            }
        },
        {
            "type": "AlphaNumeric",
            "title": "US Zipcode",
            "settings": {
                "placeholder": "xxxxx"
            }
        }
    ],
    "export": {
        "type": "XML",
        "settings": {
            "stripWhitespace": false,
            "recordNodeName":"test",
            "rootNodeName":"MyRoot",
            "dataStructureFormat": "simple"
        }
    }
    }';

        $apiCall = new Api();

        return $apiCall->apiCall($testData);

    }


    /**
     * Process the json template and generate the demo data
     * @param $request
     * @param $response
     * @param $args
     */
    public function processAction($request, $response, $args)
    {
        $templateProcessor = new TemplateProcessor();
        $templateProcessor->processTemplate('test.json');
    }


}
