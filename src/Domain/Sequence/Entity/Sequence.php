<?php

declare(strict_types=1);

namespace Bodas\Domain\Sequence\Entity;

use Bodas\Domain\Building\Entity\Floor;

class Sequence
{
    private Interval $interval;
    private Schedule $schedule;
    /** @var Floor[] $startFloors */
    private array $startFloors;
    /** @var Floor[] $destinationFloors */
    private array $destinationFloors;

    /**
     * Sequence constructor.
     *
     * @param Interval $interval
     * @param Schedule $schedule
     * @param Floor[]  $startFloor
     * @param Floor[]  $destinationFloor
     */
    public function __construct(Interval $interval, Schedule $schedule, array $startFloor, array $destinationFloor)
    {
        $this->interval          = $interval;
        $this->schedule          = $schedule;
        $this->startFloors       = $startFloor;
        $this->destinationFloors = $destinationFloor;
    }

    /**
     * @return Floor[]
     */
    public function startFloors(): array
    {
        return $this->startFloors;
    }

    /**
     * @return Floor[]
     */
    public function destinationFloors(): array
    {
        return $this->destinationFloors;
    }

    public function startTime(): \DateTimeImmutable
    {
        return $this->interval->startTime();
    }

    public function endTime(): \DateTimeImmutable
    {
        return $this->interval->endTime();
    }

    public function schedule(): Schedule
    {
        return $this->schedule;
    }
}
