<?php

declare(strict_types=1);

namespace App\Domain\ParticleBC\Event;

use App\Domain\SharedKernel\Event\EventInterface;
use App\Domain\SharedKernel\VO\QuarkId;
use App\Domain\SharedKernel\VO\QuarkType;

final class QuarkCreated implements EventInterface
{
    private \DateTimeImmutable $when;
    public function __construct(private QuarkId $id, private QuarkType $type, private bool $isAntiparticle)
    {
        $this->when = new \DateTimeImmutable();
    }
    public function occurredOn(): \DateTimeImmutable
    {
        return $this->when;
    }
    public function name(): string
    {
        return 'QuarkCreated';
    }
    public function toArray(): array
    {
        return ['id' => $this->id->toString(), 'type' => $this->type->value, 'isAntiparticle' => $this->isAntiparticle];
    }
    public function id(): QuarkId
    {
        return $this->id;
    }
}
