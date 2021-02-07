<?php

namespace Bodas\UI\Console\Command\Command;

use Bodas\Application\Simulator\Service\SequenceSimulator;
use Bodas\Domain\Building\Entity\Building;
use Bodas\Domain\Building\Entity\Floor;
use Bodas\Domain\Lift\Entity\Lift;
use Bodas\Domain\Sequence\Entity\Interval;
use Bodas\Domain\Sequence\Entity\Schedule;
use Bodas\Domain\Sequence\Entity\Sequence;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LiftSimulatorCommand extends Command
{
    const TOTAL_FLOORS = 4;
    const TOTAL_LIFTS = 3;

    private SequenceSimulator $simulator;
    /** @var Sequence[] */
    private array $sequences;
    private Building $building;

    protected function configure(): void
    {
        $this->setName('lift:simulator:run');
        $this->setDescription('Lift simulator');

        $this->simulator = new SequenceSimulator();
        $this->building  = $this->createBuilding('Testa', self::TOTAL_FLOORS, self::TOTAL_LIFTS);
        $this->sequences = [
            new Sequence(
                new Interval(9, 0, 11, 0),
                new Schedule(5),
                [$this->building->floor(0)],
                [$this->building->floor(2)]
            ),
            new Sequence(
                new Interval(9, 0, 10, 0),
                new Schedule(10),
                [$this->building->floor(0)],
                [$this->building->floor(1)]
            ),
            new Sequence(
                new Interval(11, 0, 18, 20),
                new Schedule(20),
                [$this->building->floor(0)],
                [$this->building->floor(1), $this->building->floor(2), $this->building->floor(3)]
            ),
            new Sequence(
                new Interval(14, 0, 15, 0),
                new Schedule(4),
                [$this->building->floor(1), $this->building->floor(2), $this->building->floor(3)],
                [$this->building->floor(0)]
            )
        ];
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $startTime = new \DateTimeImmutable(
            date(sprintf('Y-m-d %d:%d:00', 9, 0)),
            new \DateTimeZone('UTC')
        );

        $endTime = new \DateTimeImmutable(
            date(sprintf('Y-m-d %d:%d:00', 20, 0)),
            new \DateTimeZone('UTC')
        );

        $liftStatuses = $this->simulator->run(
            $this->building,
            $this->sequences,
            $startTime,
            $endTime
        );
        foreach ($liftStatuses as $time => $statuses) {
            echo $time . ":\n";
            $lifts = "";
            foreach ($statuses as $status) {
                $lifts .= sprintf(
                    "#%d, CurrentFloor: %d, Count: %d \n",
                    $status->liftNumber(),
                    $status->currentFloor(),
                    $status->floorsCount()
                );
            }
            echo $lifts . "\n";
        }

        return Command::SUCCESS;
    }

    private function createBuilding(string $name, int $totalFloors, int $totalLifts): Building
    {
        $floors = [];
        for ($i = 0; $i < $totalFloors; $i++) {
            array_push($floors, new Floor($i));
        }

        $baseFloor = $floors[0];

        $lifts = [];

        for ($i = 1; $i <= $totalLifts; $i++) {
            array_push($lifts, new Lift($i, $baseFloor));
        }

        return new Building($name, $floors, $lifts);
    }
}
