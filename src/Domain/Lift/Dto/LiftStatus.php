<?php

declare(strict_types=1);

namespace Bodas\Domain\Lift\Dto;

use Bodas\Domain\Lift\Entity\Lift;

class LiftStatus
{
    private int $liftNumber;
    private int $currentFloor;
    private int $floorsCount;
    private \DateTimeImmutable $time;

    public function __construct(Lift $lift, \DateTimeImmutable $time)
    {
        $this->liftNumber   = $lift->number();
        $this->currentFloor = $lift->currentFloor()->number();
        $this->floorsCount  = $lift->countFloors();
        $this->time         = $time;
    }

    public function liftNumber(): int
    {
        return $this->liftNumber;
    }

    public function currentFloor(): int
    {
        return $this->currentFloor;
    }

    public function floorsCount(): int
    {
        return $this->floorsCount;
    }
}
