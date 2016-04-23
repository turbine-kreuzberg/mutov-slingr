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


use MutovSlingr\Pickers\PickerSettings;

class PickerSettingsTest extends \PHPUnit_Framework_TestCase
{

    public function testGetProbability()
    {
        $pickerSettings = new PickerSettings($this->getPickerSettings());
        $this->assertEquals(100, $pickerSettings->getProbability());
    }

    public function testGetMin()
    {
        $pickerSettings = new PickerSettings($this->getPickerSettings());
        $this->assertEquals(1, $pickerSettings->getMin());
    }

    public function testGetMax()
    {
        $pickerSettings = new PickerSettings($this->getPickerSettings());
        $this->assertEquals(2, $pickerSettings->getMax());
    }

    /**
     * @expectedException \LogicException
     */
    public function testShouldThrowExceptionIfMinimumIsGreaterThanMaximum()
    {
        $settings = $this->getPickerSettings();
        $settings['min'] = $settings['max'] + 1;
        $pickerSettings = new PickerSettings($settings);
    }

    public function testGetSeparator()
    {
        $pickerSettings = new PickerSettings($this->getPickerSettings());
        $this->assertEquals('|', $pickerSettings->getSeparator());
    }

    /**
     * @return array
     */
    private function getPickerSettings()
    {
        return [
            'probability' => 100,
            'min' => 1,
            'max' => 2,
            'separator' => '|',
        ];
    }

}
