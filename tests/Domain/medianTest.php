<?php

namespace App\Tests\Domain;

use App\Luz\Domain\Math\Median;
use PHPUnit\Framework\TestCase;

class MedianTest extends TestCase
{
    public function testMenian()
    {
        $median = Median::calculate([1,2,3,4,5,6,7,8,9]);
        $this->assertEquals(5, $median);

        $median = Median::calculate([1,2,3,4,5,6,7,8,9,10]);
        $this->assertEquals(5.5, $median);
    }
}
