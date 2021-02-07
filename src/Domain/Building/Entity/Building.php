<?php

declare(strict_types=1);

namespace Bodas\Domain\Building\Entity;

use Bodas\Domain\Building\Exception\InvalidFloorCountException;
use Bodas\Domain\Building\Exception\InvalidLiftCountException;
use Bodas\Domain\Building\ValueObject\BuildingId;
use Bodas\Domain\Lift\Entity\Lift;

class Building
{
    private BuildingId $id;
    private string $name;
    /** @var Floor[] $floors */
    private array $floors;
    /** @var Lift[] $lifts */
    private array $lifts;

    /**
     * Building constructor.
     *
     * @param string  $name
     * @param Floor[] $floors
     * @param Lift[]  $lifts
     *
     * @throws InvalidFloorCountException
     * @throws InvalidLiftCountException
     */
    public function __construct(string $name, array $floors, array $lifts)
    {
        $this->id   = new BuildingId();
        $this->name = $name;
        if (empty($floors)) {
            throw new InvalidFloorCountException();
        }
        $this->floors = $floors;
        if (empty($lifts)) {
            throw new InvalidLiftCountException();
        }
        $this->lifts = $lifts;
    }

    public function floor(int $num): ?Floor
    {
        foreach ($this->floors as $floor) {
            if ($floor->number() === $num) {
                return $floor;
            }
        }

        return null;
    }

    public function nearestLift(Floor $floor): Lift
    {
        $lift = $this->liftAtFloor($floor);

        if ($lift === null) {
            $nearestUpwards   = $this->searchLiftUpwards($floor);
            $nearestDownwards = $this->searchLiftDownwards($floor);

            if ($nearestDownwards !== null && $nearestUpwards !== null) {
                $floorsBetweenDownwards = abs($floor->number() - $nearestDownwards->currentFloor()->number());
                $floorsBetweenUpwards   = abs($nearestUpwards->currentFloor()->number() - $floor->number());
                if ($floorsBetweenDownwards === $floorsBetweenUpwards) {
                    $lift = $nearestUpwards;
                } else {
                    $lift = $floorsBetweenUpwards > $floorsBetweenDownwards ? $nearestDownwards : $nearestUpwards;
                }
            } elseif ($nearestUpwards !== null) {
                $lift = $nearestUpwards;
            } else {
                $lift = $nearestDownwards;
            }
        }

        return $lift ?? $this->lifts()[0];
    }

    private function searchLiftUpwards(Floor $floor): ?Lift
    {
        $lift = null;
        if ($floor->number() === count($this->floors) - 1) {
            return null;
        }
        $currentFloor = clone $floor;
        do {
            $currentFloor = $this->floor($currentFloor->number() + 1);
            if ($currentFloor !== null) {
                $lift = $this->liftAtFloor($currentFloor);
            }
        } while ($lift === null && $currentFloor !== null);

        return $lift;
    }

    private function searchLiftDownwards(Floor $floor): ?Lift
    {
        $lift = null;
        if ($floor->number() === 0) {
            return null;
        }
        $currentFloor = clone $floor;
        do {
            $currentFloor = $this->floor($currentFloor->number() - 1);
            if ($currentFloor !== null) {
                $lift = $this->liftAtFloor($currentFloor);
            }
        } while ($lift === null && $currentFloor !== null);

        return $lift;
    }

    private function liftAtFloor(Floor $floor): ?Lift
    {
        $liftsAtFloor = array_filter(
            $this->lifts,
            function (Lift $l) use ($floor) {
                return ($l->currentFloor()->id()->equals($floor->id()));
            }
        );
        usort(
            $liftsAtFloor,
            function (Lift $a, Lift $b) {
                return $a->countFloors() < $b->countFloors() ? -1 : 1;
            }
        );

        return $liftsAtFloor[0] ?? null;
    }

    /**
     * @return Lift[]
     */
    public function lifts(): array
    {
        return $this->lifts;
    }

    public function lift(int $number): ?Lift
    {
        $filteredLifts = array_filter(
            $this->lifts,
            function (Lift $lift) use ($number) {
                return $lift->number() === $number;
            }
        );

        return !empty($filteredLifts) ? current($filteredLifts) : null;
    }

}
