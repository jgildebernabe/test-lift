<?php

declare(strict_types=1);

namespace Bodas\Tests\functional\Simulator\Service;

use Bodas\Application\Simulator\Service\SequenceSimulator;
use Bodas\Domain\Building\Entity\Building;
use Bodas\Domain\Building\Entity\Floor;
use Bodas\Domain\Lift\Dto\LiftStatus;
use Bodas\Domain\Lift\Entity\Lift;
use Bodas\Domain\Sequence\Entity\Interval;
use Bodas\Domain\Sequence\Entity\Schedule;
use Bodas\Domain\Sequence\Entity\Sequence;
use PHPUnit\Framework\TestCase;

class SequenceSimulatorTest extends TestCase
{
    private SequenceSimulator $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new SequenceSimulator();
    }

    private function getBuilding(): Building
    {
        $floors = [
            new Floor(0),
            new Floor(1),
            new Floor(2),
            new Floor(3)
        ];

        $lifts = [
            new Lift(1, $floors[0]),
            new Lift(2, $floors[0]),
            new Lift(3, $floors[0])
        ];

        return new Building('TEST', $floors, $lifts);
    }

    public function testEmptySequence()
    {
        $building = $this->getBuilding();
        $start    = new \DateTimeImmutable(
            'now',
            new \DateTimeZone('UTC')
        );
        $statuses = $this->sut->run($building, [], $start, $start->modify('+10 minute'));
        $this->assertCount(11, $statuses);
        foreach ($statuses as $status) {
            $this->assertCount(count($building->lifts()), $status);
        }
    }


    public function testSequence()
    {
        $building = $this->getBuilding();
        $start    = (new \DateTimeImmutable('now'))->setTime((int)date('H'), (int)date('i'), 0);

        $end      = $start->modify('+5 minutes');
        $statuses = $this->sut->run(
            $building,
            [
                new Sequence(
                    new Interval(
                        (int)$start->format('H'),
                        (int)$start->format('i'),
                        (int)$end->format('H'),
                        (int)$end->format('i')
                    ),
                    new Schedule(1),
                    [$building->floor(0)],
                    [$building->floor(1)]
                ),
                new Sequence(
                    new Interval(
                        (int)$start->format('H'),
                        (int)$start->format('i'),
                        (int)$end->format('H'),
                        (int)$end->format('i')
                    ),
                    new Schedule(1),
                    [$building->floor(3)],
                    [$building->floor(1)]
                )
            ],
            $start,
            $start->modify('+10 minute')
        );
        foreach (end($statuses) as $status) {
            $lift = $building->lift($status->liftNumber());
            $this->assertInstanceOf(Lift::class, $lift);
            $this->assertEquals($lift->number(), $status->liftNumber());
            $this->assertEquals($lift->currentFloor()->number(), $status->currentFloor());
            $this->assertEquals($lift->countFloors(), $status->floorsCount());
        }
    }

}
