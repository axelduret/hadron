<?php

declare(strict_types=1);

namespace App\Domain\ParticleBC\Repository;

use App\Domain\ParticleBC\Aggregates\QuarkAggregate;

interface QuarkWriteRepositoryInterface
{
    public function save(QuarkAggregate $quark): void;
}
