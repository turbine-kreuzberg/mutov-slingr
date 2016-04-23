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
use MutovSlingr\Pickers\RandomPicker;

class RandomPickerTest extends Test
{
    public function testShouldReturnNoPickedValueIfOutOfProbability()
    {
        $randomPicker = new RandomPicker(['min' => 1, 'max' => 10, 'probability' => -1]);
        $values = $randomPicker->pickValues([], 'field');
        $this->assertEquals([], $values);
    }

    /**
     * @expectedException \LogicException
     */
    public function testShouldThrowExceptionIfMinimumIsGreaterThanMaximum()
    {
        $randomPicker = new RandomPicker(['min' => 10, 'max' => 1]);
        $randomPicker->pickValues([], 'field');
    }

    public function testShouldReturnSinglePickIfOnlyOneElementToPick()
    {
        $randomPicker = new RandomPicker(['min' => 1, 'max' => 1]);
        $values = $randomPicker->pickValues(
            [['field' => 'value'], ['field' => 'value2']],
            'field'
        );
        $this->assertCount(1, $values);
    }

    public function testShouldReturnAtLeastNumberOfMinimumValues()
    {
        $randomPicker = new RandomPicker(['min' => 2, 'max' => 3]);
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
        $randomPicker = new RandomPicker(['min' => 2, 'max' => 3]);
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
        $this->assertLessThanOrEqual(3, count($values));
    }

    public function testShouldReturnTheExactValues()
    {
        $randomPicker = new RandomPicker(['min' => 2, 'max' => 2]);
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
        $randomPicker = new RandomPicker(['min' => 2, 'max' => 2, 'separator' => '|']);
        $values = $randomPicker->pickValues(
            [
                ['field' => 'value'],
                ['field' => 'value2'],
            ],
            'field'
        );
        $this->assertEquals('value|value2', $values);
    }


    
}
