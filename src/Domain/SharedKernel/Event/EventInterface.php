<?php
declare(strict_types=1);
namespace App\Domain\SharedKernel\Event;
interface EventInterface
{
    public function occurredOn(): \DateTimeImmutable;
    public function toArray(): array;
    public function name(): string;
}
