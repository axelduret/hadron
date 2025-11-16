<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories;
use App\Domain\HadronsBC\Aggregates\HadronAggregate;
use App\Domain\HadronsBC\Repository\HadronWriteRepositoryInterface;
use App\Domain\HadronsBC\Repository\HadronReadRepositoryInterface;
final class InMemoryHadronRepository implements HadronWriteRepositoryInterface, HadronReadRepositoryInterface
{
    private array $store = [];
    public function save(HadronAggregate $hadron): void { $this->store[] = $hadron; }
    public function findAll(): array { return $this->store; }
}
