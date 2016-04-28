<?php
/**
 * Copyright notice
 *
 *
 * @author: Christian Müllenhagen <christian.muellenhagen@votum.de> - VOTUM GmbH
 * All rights reserved
 * @date: 28.04.16
 */

namespace MutovSlingr\Test\Unit\Pickers;


use Codeception\TestCase\Test;
use MutovSlingr\Pickers\PickerFactory;
use MutovSlingr\Pickers\PickerInterface;

class PickerFactoryTest extends Test
{

    /**
     * @expectedException \ErrorException
     */
    public function testShouldThrowExceptionForSettingsWithoutType()
    {
        $pickerFactory = new PickerFactory();
        $pickerFactory->createPicker([]);
    }

    /**
     * @expectedException \ErrorException
     */
    public function testShouldThrowExceptionForInvalidPickerType()
    {
        $pickerFactory = new PickerFactory();
        $pickerFactory->createPicker(['type' => 'INVALID_TYPE']);
    }

    public function testShouldReturnPickerInstance()
    {
        $pickerFactory = new PickerFactory();
        $this->assertInstanceOf(
            PickerInterface::class,
            $pickerFactory->createPicker(['type' => 'Random'])
        );
    }
    
}
