<?php

declare(strict_types=1);

namespace App\Domain\HadronsBC\Repository;

use App\Domain\HadronsBC\Aggregates\HadronAggregate;

interface HadronReadRepositoryInterface
{
    /** @return array<HadronAggregate> */
    public function findAll(): array;
}
