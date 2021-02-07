<?php

declare(strict_types=1);

namespace Bodas\Domain\Building\Entity;

use Bodas\Domain\Building\ValueObject\FloorId;

class Floor
{
    private FloorId $id;
    private int $num;

    public function __construct(int $num)
    {
        $this->id  = new FloorId();
        $this->num = $num;
    }

    public function id(): FloorId
    {
        return $this->id;
    }

    public function number(): int
    {
        return $this->num;
    }
}
