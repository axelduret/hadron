<?php
declare(strict_types=1);
namespace App\Domain\SharedKernel\VO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use InvalidArgumentException;

final class QuarkId
{
    private UuidInterface $uuid;
    private function __construct(UuidInterface $uuid) { $this->uuid = $uuid; }
    public static function generate(): self { return new self(Uuid::uuid4()); }
    public static function fromString(string $id): self {
        if (!Uuid::isValid($id)) throw new InvalidArgumentException("Invalid Quark UUID: $id");
        return new self(Uuid::fromString($id));
    }
    public function toString(): string { return $this->uuid->toString(); }
    public function equals(self $other): bool { return $this->uuid->equals($other->uuid); }
}
