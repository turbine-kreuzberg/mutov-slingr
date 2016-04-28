<?php
/**
 * Copyright notice
 *
 *
 * @author: Christian Müllenhagen <christian.muellenhagen@votum.de> - VOTUM GmbH
 * All rights reserved
 * @date: 23.04.16
 */

namespace MutovSlingr\Test\Unit\Pickers;


use Codeception\TestCase\Test;
use MutovSlingr\Model\Api;
use MutovSlingr\Pickers\PickerFactory;
use MutovSlingr\Pickers\PickerInterface;
use MutovSlingr\Pickers\SinglePicker;
use MutovSlingr\Processor\TemplateProcessor;
use Slim\Interfaces\CollectionInterface;

class TemplateProcessorTest extends Test
{

    /**
     * @var TemplateProcessor
     */
    private $templateProcessor;

    public function setUp()
    {
        $configMock = $this->getConfigMock();
        $configMock->expects($this->any())
            ->method('get')
            ->willReturn([]);
        $apiMock = $this->getApiMock();
        $apiMock->expects($this->any())
            ->method('apiCall')
            ->willReturn('["resultFromApi"]');

        $this->templateProcessor = new TemplateProcessor($apiMock, $configMock, $this->getPickerFactoryMock());
    }
    /**
     * @expectedException \ErrorException
     */
    public function testShouldThrowExceptionWithout()
    {
        $this->templateProcessor->processTemplate([]);
    }

    public function testShouldReturnEmptyListWithoutTemplateContentGiven()
    {
        $data = $this->templateProcessor->processTemplate(['templates' => []]);
        $this->assertEquals([], $data);
    }

    public function testShouldReturnFlatDataList()
    {
        $template = ['templates' =>
            [
                [
                'label' => 'Test',
                'definition' => ['definition'],
                ]
            ]
        ];
        $data = $this->templateProcessor->processTemplate($template);
        $this->assertEquals(['Test' => ['resultFromApi']], $data);
    }

    public function testShouldReturnListWithRelations()
    {
        $template = [
            'templates' => [
                [
                    'label' => 'TestObject',
                    'definition' => ['definition'],
                ],
                [
                    'label' => 'TestForeignObject',
                    'definition' => [
                        'rows' => ['title' => 'id']
                    ],
                ],
            ],
            'relations' => [
                'TestObject' => [
                    'ForeignObjectId' =>
                        [
                        'foreignObject' => 'TestForeignObject',
                        'foreignField' => 'id',
                        'columnTo' => 'columnToName',
                        'pickerSettings' => [],
                    ]
                ]
            ],
        ];
        $configMock = $this->getConfigMock();
        $configMock->expects($this->any())
            ->method('get')
            ->willReturn([]);

        $apiMock = $this->getApiMock();

        $apiData = [['TestForeignObject' => []]];

        $apiMock->expects($this->at(0))
            ->method('apiCall')
            ->willReturn(json_encode($apiData));

        $apiMock->expects($this->at(1))
            ->method('apiCall')
            ->willReturn(json_encode(['resultFromApi']));

        $resultData = $apiData;
        $resultData[0]['ForeignObjectId'] = ['pickedValue'];

        $templateProcessor = new TemplateProcessor($apiMock, $configMock, $this->getPickerFactoryMock());
        $data = $templateProcessor->processTemplate($template);
        $this->assertEquals(
            [
                'TestObject' => $resultData,
                'TestForeignObject' => ['resultFromApi']
            ]
            , $data
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testShouldThrowExceptionWithInvalidPostProcessorType()
    {
        $template = [
            'templates' => [],
            'postprocessors' => [
                ['type' => 'INVALID_POST_PROCESSOR_TYPE']
            ],
        ];
        $this->templateProcessor->processTemplate($template);
    }

    /**
     * @return \Slim\Interfaces\CollectionInterface
     */
    private function getConfigMock()
    {
        return $this->getMockBuilder(CollectionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \MutovSlingr\Model\Api
     */
    private function getApiMock()
    {
        return $this->getMockBuilder(Api::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return PickerFactory
     */
    private function getPickerFactoryMock()
    {
        $pickerInterfaceMock = $this->getMockBuilder(PickerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pickerInterfaceMock->expects($this->any())
            ->method('pickValues')
            ->willReturn(['pickedValue']);

        $pickerFactoryMock = $this->getMockBuilder(PickerFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pickerFactoryMock->expects($this->any())
            ->method('createPicker')
            ->willReturn($pickerInterfaceMock);

        return $pickerFactoryMock;
    }
}
