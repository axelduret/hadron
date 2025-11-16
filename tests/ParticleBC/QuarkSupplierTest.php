<?php

declare(strict_types=1);

namespace Tests\ParticleBC;

use PHPUnit\Framework\TestCase;
use App\Application\ParticleBC\Command\RegisterQuarkCommand;
use App\Application\ParticleBC\Command\RegisterQuarkHandler;
use App\Infrastructure\Repositories\InMemoryQuarkRepository;
use App\Infrastructure\EventStore\InMemoryEventStore;

final class QuarkSupplierTest extends TestCase
{
    public function testRegisterQuarkWithAntiparticleFlag(): void
    {
        $repo = new InMemoryQuarkRepository();
        $eventStore = new InMemoryEventStore();
        $handler = new RegisterQuarkHandler($repo, $eventStore);
        $quark = $handler->handle(new RegisterQuarkCommand('up', false));
        $anti = $handler->handle(new RegisterQuarkCommand('up', true));
        $this->assertFalse($quark->isAntiparticle());
        $this->assertTrue($anti->isAntiparticle());
        $found = $repo->find($quark->id());
        $this->assertNotNull($found);
    }
}
