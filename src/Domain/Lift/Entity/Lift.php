<?php

declare(strict_types=1);

namespace Bodas\Domain\Lift\Entity;

use Bodas\Domain\Building\Entity\Floor;
use Bodas\Domain\Lift\ValueObject\LiftId;

class Lift
{
    private LiftId $id;
    private int $number;
    private Floor $currentFloor;
    private int $countFloors = 0;

    public function __construct(int $number, Floor $currentFloor)
    {
        $this->id           = new LiftId();
        $this->number       = $number;
        $this->currentFloor = $currentFloor;
    }

    public function number(): int
    {
        return $this->number;
    }

    public function currentFloor(): Floor
    {
        return $this->currentFloor;
    }

    public function countFloors(): int
    {
        return $this->countFloors;
    }

    public function moveTo(Floor $destinationFloor): self
    {
        if (!$destinationFloor->id()->equals($this->currentFloor->id())) {
            $this->countFloors  += abs($destinationFloor->number() - $this->currentFloor->number());
            $this->currentFloor = $destinationFloor;
        }

        return $this;
    }

}
