<?php

declare(strict_types=1);

namespace Bodas\Tests\unit\Domain\Building;

use Bodas\Domain\Building\Entity\Building;
use Bodas\Domain\Building\Entity\Floor;
use Bodas\Domain\Building\Exception\InvalidFloorCountException;
use Bodas\Domain\Building\Exception\InvalidLiftCountException;
use Bodas\Domain\Lift\Entity\Lift;
use PHPUnit\Framework\TestCase;

class BuildingTest extends TestCase
{
    public function testGetFloor()
    {
        $floors   = [
            new Floor(0),
            new Floor(1)
        ];
        $lifts    = [
            new Lift(1, $floors[0])
        ];
        $building = new Building('TEST', $floors, $lifts);
        $floor    = $building->floor(1);
        $this->assertTrue($floor->id()->equals($floors[1]->id()));
        $this->assertCount(count($lifts), $building->lifts());
    }

    public function testGetInvalidFloor()
    {
        $floors   = [
            new Floor(0),
            new Floor(1)
        ];
        $lifts    = [
            new Lift(1, $floors[0])
        ];
        $building = new Building('TEST', $floors, $lifts);
        $floor    = $building->floor(4);
        $this->assertNull($floor);
    }

    public function testInvalidFloorsBuilding()
    {
        $this->expectException(InvalidFloorCountException::class);
        $building = new Building('TEST', [], []);
    }

    public function testInvalidLiftCountBuilding()
    {
        $this->expectException(InvalidLiftCountException::class);
        $building = new Building('TEST', [new Floor(0)], []);
    }

    public function testLiftAtFloor()
    {
        $floors   = [
            new Floor(0),
            new Floor(1)
        ];
        $lifts    = [
            new Lift(1, $floors[0])
        ];
        $building = new Building('TEST', $floors, $lifts);

        $lift = $building->nearestLift($floors[0]);
        $this->assertEquals($lifts[0]->number(), $lift->number());
    }

    public function testLiftSameFloorLessUsed()
    {
        $floors   = [
            new Floor(0),
            new Floor(1)
        ];
        $lifts    = [
            (new Lift(1, $floors[0]))->moveTo($floors[1])->moveTo($floors[0]),
            new Lift(2, $floors[0]),
        ];
        $building = new Building('TEST', $floors, $lifts);

        $lift = $building->nearestLift($floors[0]);
        $this->assertEquals($lifts[1]->number(), $lift->number());
    }

    public function testLiftOtherFloorDownwards()
    {
        $floors   = [
            new Floor(0),
            new Floor(1),
            new Floor(2),
            new Floor(3)
        ];
        $lifts    = [
            new Lift(1, $floors[0]),
            new Lift(2, $floors[1]),
        ];
        $building = new Building('TEST', $floors, $lifts);

        $lift = $building->nearestLift($floors[3]);
        $this->assertEquals($lifts[1]->number(), $lift->number());
    }

    public function testLiftOtherFloorUpwards()
    {
        $floors   = [
            new Floor(0),
            new Floor(1),
            new Floor(2),
            new Floor(3)
        ];
        $lifts    = [
            new Lift(1, $floors[2]),
            new Lift(2, $floors[3]),
        ];
        $building = new Building('TEST', $floors, $lifts);

        $lift = $building->nearestLift($floors[0]);
        $this->assertEquals($lifts[0]->number(), $lift->number());
    }

    public function testLiftOtherFloorBetween()
    {
        $floors   = [
            new Floor(0),
            new Floor(1),
            new Floor(2),
            new Floor(3),
            new Floor(4)
        ];
        $lifts    = [
            new Lift(1, $floors[1]),
            new Lift(2, $floors[4]),
        ];
        $building = new Building('TEST', $floors, $lifts);

        $lift = $building->nearestLift($floors[2]);
        $this->assertEquals($lifts[0]->number(), $lift->number());
    }

    public function testLiftOtherFloorBetweenEquals()
    {
        $floors   = [
            new Floor(0),
            new Floor(1),
            new Floor(2),
            new Floor(3),
            new Floor(4)
        ];
        $lifts    = [
            new Lift(1, $floors[1]),
            new Lift(2, $floors[3]),
        ];
        $building = new Building('TEST', $floors, $lifts);

        $lift = $building->nearestLift($floors[2]);
        $this->assertEquals($lifts[1]->number(), $lift->number());
    }

}
