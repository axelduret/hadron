<?php
declare(strict_types=1);
namespace App\Domain\HadronsBC\Service;
use App\Domain\HadronsBC\Policy\HadronCompositionPolicy;
use App\Domain\HadronsBC\Aggregates\HadronAggregate;
use App\Domain\HadronsBC\Repository\HadronWriteRepositoryInterface;

final class HadronCreationService
{
    public function __construct(private HadronCompositionPolicy $policy, private HadronWriteRepositoryInterface $hadronRepo) {}
    /** @param string[] $quarkIdStrings */
    public function createFromQuarkIds(array $quarkIdStrings): HadronAggregate {
        $resolved = $this->policy->validateAndResolve($quarkIdStrings);
        /** @var \App\Domain\SharedKernel\VO\QuarkId[] $quarkIds */
        $quarkIds = $resolved['quarkIds'];
        $type = $resolved['type'];
        $hadron = HadronAggregate::reconstitute($quarkIds, $type);
        $this->hadronRepo->save($hadron);
        return $hadron;
    }
}
