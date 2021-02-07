<?php

declare(strict_types=1);

namespace Bodas\Application\Simulator\Service;

use Bodas\Domain\Building\Entity\Building;
use Bodas\Domain\Lift\Dto\LiftStatus;
use Bodas\Domain\LiftRequest\Dto\LiftRequest;
use Bodas\Domain\Sequence\Entity\Sequence;
use DateTime;

class SequenceSimulator
{
    /**
     * @param Building           $building
     * @param Sequence[]         $sequences
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     *
     * @return array<string,LiftStatus[]>
     */
    public function run(Building $building, array $sequences, \DateTimeImmutable $start, \DateTimeImmutable $end): array
    {
        $liftRequests = $this->flattenLiftRequests($sequences);
        $liftStatuses = [];

        while ($start->getTimestamp() <= $end->getTimestamp()) {
            foreach ($this->liftRequestsOnTime($liftRequests, $start) as $request) {
                $nearestLift = $building->nearestLift($request->fromFloor());
                $nearestLift->moveTo($request->fromFloor())->moveTo($request->destinationFloor());
            }
            $key                = $start->format('H:i');
            $liftStatuses[$key] = [];
            foreach ($building->lifts() as $lift) {
                array_push($liftStatuses[$key], new LiftStatus($lift, $start));
            }

            $start = $start->add(new \DateInterval('PT1M'));
        }

        return $liftStatuses;
    }

    /**
     * @param LiftRequest[]      $liftRequests
     * @param \DateTimeImmutable $currentTime
     *
     * @return LiftRequest[]
     */
    private function liftRequestsOnTime(array $liftRequests, \DateTimeImmutable $currentTime): array
    {
        return array_filter(
            $liftRequests,
            function (LiftRequest $request) use ($currentTime) {
                return $request->time()->getTimestamp() === $currentTime->getTimestamp();
            }
        );
    }

    /**
     * @param Sequence[] $sequences
     *
     * @return LiftRequest[]
     */
    private function flattenLiftRequests(array $sequences): array
    {
        $liftRequests = [];
        foreach ($sequences as $sequence) {
            $endTime   = $sequence->endTime();
            $startTime = DateTime::createFromImmutable($sequence->startTime());
            while ($startTime->getTimestamp() <= $endTime->getTimestamp()) {
                foreach ($sequence->startFloors() as $startFloor) {
                    foreach ($sequence->destinationFloors() as $destinationFloor) {
                        $request = new LiftRequest(
                            $startFloor,
                            $destinationFloor,
                            \DateTimeImmutable::createFromMutable($startTime)
                        );
                        array_push($liftRequests, $request);
                    }
                }

                $startTime = $startTime->add($sequence->schedule()->value());
            }
        }

        usort(
            $liftRequests,
            function (LiftRequest $a, LiftRequest $b) {
                if ($a->time()->getTimestamp() === $b->time()->getTimestamp()) {
                    return 0;
                }

                return $a->time()->getTimestamp() < $b->time()->getTimestamp() ? -1 : 1;
            }
        );

        return $liftRequests;
    }
}
