<?php

declare(strict_types=1);

namespace App\Infrastructure\EventStore;

use App\Domain\SharedKernel\Event\EventInterface;

interface EventStoreInterface
{
    public function append(string $streamName, array $events): void;
    public function read(string $streamName, ?int $since = null): array;
}
