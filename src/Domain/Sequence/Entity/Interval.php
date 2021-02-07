<?php

declare(strict_types=1);

namespace Bodas\Domain\Sequence\Entity;

class Interval
{
    private \DateTimeImmutable $start;
    private \DateTimeImmutable $end;

    public function __construct(int $startHour, int $startMinute, int $endHour, int $endMinute)
    {
        $this->start = new \DateTimeImmutable(
            date(sprintf('Y-m-d %d:%d:00', $startHour, $startMinute)),
            new \DateTimeZone('UTC')
        );

        $this->end = new \DateTimeImmutable(
            date(sprintf('Y-m-d %d:%d:00', $endHour, $endMinute)),
            new \DateTimeZone('UTC')
        );
    }

    public function startTime(): \DateTimeImmutable
    {
        return $this->start;
    }

    public function endTime(): \DateTimeImmutable
    {
        return $this->end;
    }

}
