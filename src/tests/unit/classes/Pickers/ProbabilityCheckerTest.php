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
use MutovSlingr\Pickers\ProbabilityChecker;

class ProbabilityCheckerTest extends Test
{

    public function testShouldReturnHitForProbabilityNotInt()
    {
        $probabilityChecker = new ProbabilityChecker();
        $this->assertTrue($probabilityChecker->hit("NO_INT"));
    }

    public function testShouldReturnHitForProbabilityIsHigherThanRandom()
    {
        mt_srand(100);
        $probabilityChecker = new ProbabilityChecker();
        $this->assertTrue($probabilityChecker->hit(47));
    }

    public function testShouldReturnNoHitForProbabilityIsLowerThanRandom()
    {
        mt_srand(100);
        $probabilityChecker = new ProbabilityChecker();
        $this->assertFalse($probabilityChecker->hit(45));
    }
}
