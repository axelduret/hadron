<?php
declare(strict_types=1);
namespace App\Infrastructure\EventStore;
use App\Domain\SharedKernel\Event\EventInterface;
final class InMemoryEventStore implements EventStoreInterface
{
    private array $store = [];
    public function append(string $streamName, array $events): void {
        if (!isset($this->store[$streamName])) $this->store[$streamName] = [];
        foreach ($events as $e) $this->store[$streamName][] = $e;
    }
    public function read(string $streamName, ?int $since = null): array {
        $events = $this->store[$streamName] ?? [];
        if ($since === null) return $events;
        return array_slice($events, $since);
    }
    public function allStreams(): array { return $this->store; }
}
