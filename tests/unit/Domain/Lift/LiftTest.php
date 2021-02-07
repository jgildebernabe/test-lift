<?php

declare(strict_types=1);

namespace Bodas\Tests\unit\Domain\Lift;

use Bodas\Domain\Building\Entity\Floor;
use Bodas\Domain\Lift\Entity\Lift;
use PHPUnit\Framework\TestCase;

class LiftTest extends TestCase
{
    public function testMoveLift()
    {
        $currentFloor     = new Floor(0);
        $destinationFloor = new Floor(3);
        $lift             = new Lift(1, $currentFloor);
        $lift->moveTo($destinationFloor);
        $this->assertEquals($lift->currentFloor()->number(), $destinationFloor->number());
        $this->assertEquals($destinationFloor->number() - $currentFloor->number(), $lift->countFloors());
    }

    public function testMoveLiftSameFloor()
    {
        $currentFloor = new Floor(1);
        $lift         = new Lift(1, $currentFloor);
        $lift->moveTo($currentFloor);
        $this->assertEquals(0, $lift->countFloors());
    }


}
