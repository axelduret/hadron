<?php

declare(strict_types=1);

namespace App\Infrastructure\Projection;

final class QuarkProjectionRepository
{
    private array $store = [];
    public function projectQuark(array $data): void
    {
        $this->store[$data['id']] = $data;
    }
    public function find(string $id): ?array
    {
        return $this->store[$id] ?? null;
    }
    public function all(): array
    {
        return array_values($this->store);
    }
}
