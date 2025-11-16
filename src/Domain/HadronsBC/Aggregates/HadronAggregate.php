<?php
declare(strict_types=1);
namespace App\Domain\HadronsBC\Aggregates;
use App\Domain\SharedKernel\VO\QuarkId;

final class HadronAggregate
{
    private array $recordedEvents = [];
    private function __construct(private array $quarkIds, private string $type, bool $recordEvent = true) {}
    public static function reconstitute(array $quarkIds, string $type): self { return new self($quarkIds, $type, false); }
    public function quarkIds(): array { return $this->quarkIds; }
    public function type(): string { return $this->type; }
}
