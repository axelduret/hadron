<?php
declare(strict_types=1);
namespace App\Application\ParticleBC\Command;
use App\Domain\ParticleBC\Repository\QuarkWriteRepositoryInterface;
use App\Domain\ParticleBC\Aggregates\QuarkAggregate;
use App\Infrastructure\EventStore\EventStoreInterface;

final class RegisterQuarkHandler
{
    public function __construct(private QuarkWriteRepositoryInterface $repository, private EventStoreInterface $eventStore) {}
    public function handle(RegisterQuarkCommand $command): QuarkAggregate {
        $quark = QuarkAggregate::register($command->type, $command->isAntiparticle);
        $this->repository->save($quark);
        $events = $quark->pullRecordedEvents();
        if (!empty($events)) $this->eventStore->append('quark-'.$quark->id()->toString(), $events);
        return $quark;
    }
}
