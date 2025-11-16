<?php
declare(strict_types=1);
namespace App\Domain\HadronsBC\Repository;
use App\Domain\HadronsBC\Aggregates\HadronAggregate;
interface HadronWriteRepositoryInterface { public function save(HadronAggregate $hadron): void; }
