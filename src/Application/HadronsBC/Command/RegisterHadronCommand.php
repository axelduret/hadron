<?php

declare(strict_types=1);

namespace App\Application\HadronsBC\Command;

final class RegisterHadronCommand
{
    /** @param string[] $quarkIds */ public function __construct(public readonly array $quarkIds) {}
}
