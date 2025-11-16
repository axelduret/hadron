<?php

declare(strict_types=1);

namespace Tests\HadronsBC;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\EventStore\InMemoryEventStore;
use App\Domain\HadronsBC\Service\HadronCreationService;
use App\Domain\HadronsBC\Policy\HadronCompositionPolicy;
use App\Application\HadronsBC\Command\RegisterHadronCommand;
use App\Application\HadronsBC\Command\RegisterHadronHandler;
use App\Application\ParticleBC\Command\RegisterQuarkCommand;
use App\Application\ParticleBC\Command\RegisterQuarkHandler;
use App\Infrastructure\Repositories\InMemoryQuarkRepository;
use App\Infrastructure\Repositories\InMemoryHadronRepository;

final class HadronConsumerSupplierIntegrationTest extends TestCase
{
    public function testHadronCreationFailsWhenQuarkMissing(): void
    {
        $quarkRepo = new InMemoryQuarkRepository();
        $hadronRepo = new InMemoryHadronRepository();
        $policy = new HadronCompositionPolicy($quarkRepo);
        $service = new HadronCreationService($policy, $hadronRepo);
        $handler = new RegisterHadronHandler($service);
        $this->expectException(\InvalidArgumentException::class);
        $handler->handle(new RegisterHadronCommand(['00000000-0000-0000-0000-000000000000']));
    }

    public function testHadronCreationSucceedsWhenQuarksExist_Baryon(): void
    {
        $quarkRepo = new InMemoryQuarkRepository();
        $hadronRepo = new InMemoryHadronRepository();
        $registerQuarkHandler = new RegisterQuarkHandler($quarkRepo, new InMemoryEventStore());
        $q1 = $registerQuarkHandler->handle(new RegisterQuarkCommand('up', false));
        $q2 = $registerQuarkHandler->handle(new RegisterQuarkCommand('down', false));
        $q3 = $registerQuarkHandler->handle(new RegisterQuarkCommand('up', false));
        $policy = new HadronCompositionPolicy($quarkRepo);
        $service = new HadronCreationService($policy, $hadronRepo);
        $handler = new RegisterHadronHandler($service);
        $hadron = $handler->handle(new RegisterHadronCommand([$q1->id()->toString(), $q2->id()->toString(), $q3->id()->toString()]));
        $this->assertSame('baryon', $hadron->type());
    }

    public function testHadronCreationSucceedsWhenQuarksExist_Meson(): void
    {
        $quarkRepo = new InMemoryQuarkRepository();
        $hadronRepo = new InMemoryHadronRepository();
        $registerQuarkHandler = new RegisterQuarkHandler($quarkRepo, new InMemoryEventStore());
        $q = $registerQuarkHandler->handle(new RegisterQuarkCommand('up', false));
        $anti = $registerQuarkHandler->handle(new RegisterQuarkCommand('up', true));
        $policy = new HadronCompositionPolicy($quarkRepo);
        $service = new HadronCreationService($policy, $hadronRepo);
        $handler = new RegisterHadronHandler($service);
        $hadron = $handler->handle(new RegisterHadronCommand([$q->id()->toString(), $anti->id()->toString()]));
        $this->assertSame('meson', $hadron->type());
    }

    public function testMesonCreationFailsIfBothAreParticlesOrBothAntiparticles(): void
    {
        $quarkRepo = new InMemoryQuarkRepository();
        $hadronRepo = new InMemoryHadronRepository();
        $registerQuarkHandler = new RegisterQuarkHandler($quarkRepo, new InMemoryEventStore());
        $q1 = $registerQuarkHandler->handle(new RegisterQuarkCommand('up', false));
        $q2 = $registerQuarkHandler->handle(new RegisterQuarkCommand('down', false));
        $policy = new HadronCompositionPolicy($quarkRepo);
        $service = new HadronCreationService($policy, $hadronRepo);
        $handler = new RegisterHadronHandler($service);
        $this->expectException(\InvalidArgumentException::class);
        $handler->handle(new RegisterHadronCommand([$q1->id()->toString(), $q2->id()->toString()]));
    }
}
