<?php
declare(strict_types=1);
namespace App\Infrastructure\Repositories;
use App\Domain\ParticleBC\Aggregates\QuarkAggregate;
use App\Domain\ParticleBC\Repository\QuarkWriteRepositoryInterface;
use App\Domain\ParticleBC\Repository\QuarkReadRepositoryInterface;
use App\Domain\SharedKernel\VO\QuarkId;
use App\Domain\SharedKernel\VO\QuarkType;
final class InMemoryQuarkRepository implements QuarkWriteRepositoryInterface, QuarkReadRepositoryInterface
{
    private array $store = [];
    public function save(QuarkAggregate $quark): void {
        $this->store[$quark->id()->toString()] = ['id'=>$quark->id()->toString(),'type'=>$quark->type()->value,'isAntiparticle'=>$quark->isAntiparticle()];
    }
    public function find(QuarkId $id): ?QuarkAggregate {
        $key = $id->toString();
        if (!isset($this->store[$key])) return null;
        $s = $this->store[$key];
        return QuarkAggregate::reconstituteFromState(QuarkId::fromString($s['id']), QuarkType::from($s['type']), (bool)$s['isAntiparticle']);
    }
}
