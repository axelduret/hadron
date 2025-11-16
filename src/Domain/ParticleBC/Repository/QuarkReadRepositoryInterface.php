<?php
declare(strict_types=1);
namespace App\Domain\ParticleBC\Repository;
use App\Domain\ParticleBC\Aggregates\QuarkAggregate;
use App\Domain\SharedKernel\VO\QuarkId;
interface QuarkReadRepositoryInterface { public function find(QuarkId $id): ?QuarkAggregate; }
