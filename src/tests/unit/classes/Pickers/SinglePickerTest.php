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
use MutovSlingr\Pickers\SinglePicker;

class SinglePickerTest extends Test
{
    public function testShouldReturnNoPickedValueIfOutOfProbability()
    {
        $probabilityCheckerMock = $this->getMockBuilder(ProbabilityChecker::class)->getMock();

        $probabilityCheckerMock->expects($this->any())->method('hit')->willReturn(false);
        $singlePicker = new SinglePicker(new PickerSettings(['probability' => -1]), $probabilityCheckerMock);
        $values = $singlePicker->pickValues([], 'field');
        $this->assertEquals([], $values);
    }

    public function testShouldReturnEmptyValueIfForeignObjectIsEmpty()
    {
        $singlePicker = new SinglePicker(new PickerSettings([]), $this->getProbabilityCheckerMock());
        $values = $singlePicker->pickValues([], 'field');
        $this->assertEquals([], $values);
    }

    public function testShouldReturnSinglePickIfOnlyOneElementToPick()
    {
        $singlePicker = new SinglePicker(new PickerSettings([]), $this->getProbabilityCheckerMock());
        $values = $singlePicker->pickValues([['field' => 'value']], 'field');
        $this->assertEquals(['value'], $values);
    }

    public function testShouldReturnSinglePickIfMultipleElementsToPick()
    {
        $singlePicker = new SinglePicker(new PickerSettings([]), $this->getProbabilityCheckerMock());
        $values = $singlePicker->pickValues(
            [['field' => 'value'], ['field' => 'value2']],
            'field'
        );
        $this->assertCount(1, $values);
    }
    
    private function getProbabilityCheckerMock()
    {
        $probabilityCheckerMock = $this->getMockBuilder(ProbabilityChecker::class)->getMock();
        $probabilityCheckerMock->expects($this->any())->method('hit')->willReturn(true);
        return $probabilityCheckerMock;
    }
}

