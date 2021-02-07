<?php

declare(strict_types=1);

namespace Bodas\Domain\Sequence\Entity;

class Schedule
{
    private \DateInterval $schedule;

    public function __construct(int $minutes)
    {
        $this->schedule = new \DateInterval(sprintf('PT%dM', abs($minutes)));
    }

    public function value(): \DateInterval
    {
        return $this->schedule;
    }

}
