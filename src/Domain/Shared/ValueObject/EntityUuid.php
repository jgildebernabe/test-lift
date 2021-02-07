<?php

declare(strict_types=1);

namespace Bodas\Domain\Shared\ValueObject;

use Ramsey\Uuid\Uuid;
use Stringable;

class EntityUuid implements Stringable
{
    protected string $value;

    public function __construct(string $value = null)
    {
        $this->value = Uuid::fromString($value ?? (string)Uuid::uuid4())->toString();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(EntityUuid $entityUuid): bool
    {
        return $this->value() === $entityUuid->value();
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
