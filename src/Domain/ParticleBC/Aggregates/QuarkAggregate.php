<?php

declare(strict_types=1);

namespace App\Domain\ParticleBC\Aggregates;

use App\Domain\SharedKernel\VO\QuarkId;
use App\Domain\SharedKernel\VO\QuarkType;
use App\Domain\ParticleBC\Event\QuarkCreated;

final class QuarkAggregate
{
    private array $recordedEvents = [];
    private function __construct(private QuarkId $id, private QuarkType $type, private bool $isAntiparticle = false, bool $recordEvent = true)
    {
        if ($recordEvent) $this->recordEvent(new QuarkCreated($this->id, $this->type, $this->isAntiparticle));
    }
    public static function register(string $typeString, bool $isAntiparticle = false): self
    {
        $type = QuarkType::tryFrom($typeString);
        if ($type === null) throw new \InvalidArgumentException("Invalid quark type: $typeString");
        return new self(QuarkId::generate(), $type, $isAntiparticle, true);
    }
    public static function reconstituteFromState(QuarkId $id, QuarkType $type, bool $isAntiparticle): self
    {
        return new self($id, $type, $isAntiparticle, false);
    }
    public function id(): QuarkId
    {
        return $this->id;
    }
    public function type(): QuarkType
    {
        return $this->type;
    }
    public function isAntiparticle(): bool
    {
        return $this->isAntiparticle;
    }
    private function recordEvent(object $e): void
    {
        $this->recordedEvents[] = $e;
    }
    public function pullRecordedEvents(): array
    {
        $e = $this->recordedEvents;
        $this->recordedEvents = [];
        return $e;
    }
}
