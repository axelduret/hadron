<?php

declare(strict_types=1);

namespace App\Application\HadronsBC\Command;

use App\Domain\HadronsBC\Service\HadronCreationService;

final class RegisterHadronHandler
{
    public function __construct(private HadronCreationService $creationService) {}
    public function handle(RegisterHadronCommand $command)
    {
        return $this->creationService->createFromQuarkIds($command->quarkIds);
    }
}
