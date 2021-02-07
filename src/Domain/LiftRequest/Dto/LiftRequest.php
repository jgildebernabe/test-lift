<?php

declare(strict_types=1);

namespace Bodas\Domain\LiftRequest\Dto;

use Bodas\Domain\Building\Entity\Floor;

class LiftRequest
{
    private Floor $fromFloor;
    private Floor $destinationFloor;
    private \DateTimeImmutable $time;

    public function __construct(Floor $fromFloor, Floor $destinationFloor, \DateTimeImmutable $time)
    {
        $this->fromFloor        = $fromFloor;
        $this->destinationFloor = $destinationFloor;
        $this->time             = $time;
    }

    public function time(): \DateTimeImmutable
    {
        return $this->time;
    }

    public function fromFloor(): Floor
    {
        return $this->fromFloor;
    }

    public function destinationFloor(): Floor
    {
        return $this->destinationFloor;
    }

}
