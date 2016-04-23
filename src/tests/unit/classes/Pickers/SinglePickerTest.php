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
use MutovSlingr\Pickers\SinglePicker;

class SinglePickerTest extends Test
{
    public function testShouldReturnNoPickedValueIfOutOfProbability()
    {
        $singlePicker = new SinglePicker(['probability' => -1]);
        $values = $singlePicker->pickValues([], 'field');
        $this->assertEquals([], $values);
    }

    public function testShouldReturnEmptyValueIfForeignObjectIsEmpty()
    {
        $singlePicker = new SinglePicker([]);
        $values = $singlePicker->pickValues([], 'field');
        $this->assertEquals([], $values);
    }

    public function testShouldReturnSinglePickIfOnlyOneElementToPick()
    {
        $singlePicker = new SinglePicker([]);
        $values = $singlePicker->pickValues([['field' => 'value']], 'field');
        $this->assertEquals(['value'], $values);
    }

    public function testShouldReturnSinglePickIfMultipleElementsToPick()
    {
        $singlePicker = new SinglePicker([]);
        $values = $singlePicker->pickValues(
            [['field' => 'value'], ['field' => 'value2']],
            'field'
        );
        $this->assertCount(1, $values);
    }
}
