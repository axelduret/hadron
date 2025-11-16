<?php

declare(strict_types=1);

namespace App\Domain\HadronsBC\Policy;

use App\Domain\ParticleBC\Repository\QuarkReadRepositoryInterface;
use App\Domain\SharedKernel\VO\QuarkId;
use InvalidArgumentException;

final class HadronCompositionPolicy
{
    public function __construct(private QuarkReadRepositoryInterface $quarkRepo) {}
    /** @param string[] $quarkIdStrings */
    public function validateAndResolve(array $quarkIdStrings): array
    {
        if (empty($quarkIdStrings)) throw new InvalidArgumentException('Quark id list cannot be empty');
        $quarkIds = [];
        $isAntiparticles = [];
        foreach ($quarkIdStrings as $idStr) {
            $qid = QuarkId::fromString($idStr);
            $quark = $this->quarkRepo->find($qid);
            if ($quark === null) throw new InvalidArgumentException("Quark not found: {$idStr}");
            $quarkIds[] = $qid;
            $isAntiparticles[] = $quark->isAntiparticle();
        }
        $count = count($quarkIds);
        if ($count === 2) {
            $antipCount = count(array_filter($isAntiparticles));
            if ($antipCount !== 1) throw new InvalidArgumentException('A meson must be composed of one quark and one antiquark');
            return ['type' => 'meson', 'quarkIds' => $quarkIds];
        }
        if ($count === 3) return ['type' => 'baryon', 'quarkIds' => $quarkIds];
        throw new InvalidArgumentException("Invalid quark count for hadron: {$count}");
    }
}
