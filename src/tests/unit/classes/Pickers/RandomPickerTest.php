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
use MutovSlingr\Pickers\PickerSettings;
use MutovSlingr\Pickers\ProbabilityChecker;
use MutovSlingr\Pickers\RandomPicker;

class RandomPickerTest extends Test
{
    public function testShouldReturnNoPickedValueIfOutOfProbability()
    {
        $pickerSettingsMock = $this->getSettingsMocking();

        $probabilityCheckerMock = $this->getMockBuilder(ProbabilityChecker::class)->getMock();
        $probabilityCheckerMock->expects($this->any())->method('hit')->willReturn(false);

        $randomPicker = new RandomPicker($pickerSettingsMock, $probabilityCheckerMock);
        $values = $randomPicker->pickValues([], 'field');
        $this->assertEquals([], $values);
    }

    public function testShouldReturnSinglePickIfOnlyOneElementToPick()
    {
        $pickerSettingsMock = $this->getSettingsMocking();
        $pickerSettingsMock->expects($this->any())->method('getMin')->willReturn(1);
        $pickerSettingsMock->expects($this->any())->method('getMax')->willReturn(1);
        $randomPicker = new RandomPicker($pickerSettingsMock, $this->getProbabilityCheckerMock());
        $values = $randomPicker->pickValues(
            [['field' => 'value'], ['field' => 'value2']],
            'field'
        );
        $this->assertCount(1, $values);
    }

    public function testShouldReturnAtLeastNumberOfMinimumValues()
    {

        $pickerSettingsMock = $this->getSettingsMocking();
        $pickerSettingsMock->expects($this->any())->method('getMin')->willReturn(2);
        $pickerSettingsMock->expects($this->any())->method('getMax')->willReturn(3);

        $randomPicker = new RandomPicker($pickerSettingsMock, $this->getProbabilityCheckerMock());
        $values = $randomPicker->pickValues(
            [
                ['field' => 'value'],
                ['field' => 'value2'],
                ['field' => 'value3'],
                ['field' => 'value4'],
            ],
            'field'
        );
        $this->assertGreaterThanOrEqual(2, count($values));
    }

    public function testShouldReturnAtLeastNumberOfMaximumValues()
    {
        $pickerSettingsMock = $this->getSettingsMocking();
        $pickerSettingsMock->expects($this->any())->method('getMin')->willReturn(2);
        $pickerSettingsMock->expects($this->any())->method('getMax')->willReturn(3);

        $randomPicker = new RandomPicker($pickerSettingsMock, $this->getProbabilityCheckerMock());
        $values = $randomPicker->pickValues(
            [
                ['field' => 'value'],
                ['field' => 'value2'],
                ['field' => 'value3'],
                ['field' => 'value4'],
                ['field' => 'value5'],
                ['field' => 'value6'],
            ],
            'field'
        );
        $this->assertGreaterThanOrEqual(2, count($values));
        $this->assertLessThanOrEqual(3, count($values));
    }

    public function testShouldReturnTheExactValues()
    {
        $pickerSettingsMock = $this->getSettingsMocking();
        $pickerSettingsMock->expects($this->any())->method('getMin')->willReturn(2);
        $pickerSettingsMock->expects($this->any())->method('getMax')->willReturn(2);

        $randomPicker = new RandomPicker($pickerSettingsMock, $this->getProbabilityCheckerMock());
        $values = $randomPicker->pickValues(
            [
                ['field' => 'value'],
                ['field' => 'value2'],
            ],
            'field'
        );
        $this->assertEquals(['value','value2'], $values);
    }

    public function testShouldReturnStringOfSeparatedValues()
    {
        $pickerSettingsMock = $this->getSettingsMocking();
        $pickerSettingsMock->expects($this->any())->method('getMin')->willReturn(2);
        $pickerSettingsMock->expects($this->any())->method('getMax')->willReturn(2);
        $pickerSettingsMock->expects($this->any())->method('getSeparator')->willReturn('|');

        $randomPicker = new RandomPicker($pickerSettingsMock, $this->getProbabilityCheckerMock());
        $values = $randomPicker->pickValues(
            [
                ['field' => 'value'],
                ['field' => 'value2'],
            ],
            'field'
        );
        $this->assertEquals('value|value2', $values);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ProbabilityChecker
     */
    private function getProbabilityCheckerMock()
    {
        $probabilityCheckerMock = $this->getMockBuilder(ProbabilityChecker::class)->getMock();
        $probabilityCheckerMock->expects($this->any())->method('hit')->willReturn(true);
        return $probabilityCheckerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|PickerSettings
     */
    private function getSettingsMocking()
    {
        $pickerSettingsMock = $this->getMockBuilder(PickerSettings::class)
            ->disableOriginalConstructor()
            ->getMock();
        return $pickerSettingsMock;
    }


}
