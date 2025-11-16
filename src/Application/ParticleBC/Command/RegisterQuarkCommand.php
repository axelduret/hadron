<?php
declare(strict_types=1);
namespace App\Application\ParticleBC\Command;
final class RegisterQuarkCommand { public function __construct(public readonly string $type, public readonly bool $isAntiparticle = false) {} }
