<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';
use Slim\Factory\AppFactory;
use App\Infrastructure\Repositories\InMemoryQuarkRepository;
use App\Infrastructure\EventStore\InMemoryEventStore;
use App\Application\ParticleBC\Command\RegisterQuarkHandler;
use App\Application\ParticleBC\Command\RegisterQuarkCommand;
use App\Infrastructure\Repositories\InMemoryHadronRepository;
use App\Domain\HadronsBC\Policy\HadronCompositionPolicy;
use App\Domain\HadronsBC\Service\HadronCreationService;
use App\Application\HadronsBC\Command\RegisterHadronHandler;
use App\Application\HadronsBC\Command\RegisterHadronCommand;

$quarkRepo = new InMemoryQuarkRepository();
$eventStore = new \App\Infrastructure\EventStore\InMemoryEventStore();
$quarkHandler = new RegisterQuarkHandler($quarkRepo, $eventStore);

$hadronRepo = new InMemoryHadronRepository();
$policy = new HadronCompositionPolicy($quarkRepo);
$hadronService = new HadronCreationService($policy, $hadronRepo);
$hadronHandler = new RegisterHadronHandler($hadronService);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$app->post('/quarks', function ($req, $res) use ($quarkHandler) {
    $data = $req->getParsedBody();
    $cmd = new RegisterQuarkCommand($data['type'] ?? '', $data['isAntiparticle'] ?? false);
    $q = $quarkHandler->handle($cmd);
    $payload = ['id'=>$q->id()->toString(),'type'=>$q->type()->value,'isAntiparticle'=>$q->isAntiparticle()];
    $res->getBody()->write(json_encode($payload));
    return $res->withHeader('Content-Type','application/json')->withStatus(201);
});

$app->get('/quarks/{id}', function ($req, $res, $args) use ($quarkRepo) {
    $id = \App\Domain\SharedKernel\VO\QuarkId::fromString($args['id']);
    $q = $quarkRepo->find($id);
    if ($q === null) return $res->withStatus(404);
    $res->getBody()->write(json_encode(['id'=>$q->id()->toString(),'type'=>$q->type()->value,'isAntiparticle'=>$q->isAntiparticle()]));
    return $res->withHeader('Content-Type','application/json');
});

$app->post('/hadrons', function ($req, $res) use ($hadronHandler) {
    $data = $req->getParsedBody();
    $cmd = new RegisterHadronCommand($data['quarkIds'] ?? []);
    $h = $hadronHandler->handle($cmd);
    $res->getBody()->write(json_encode(['type'=>$h->type(),'quarkIds'=>array_map(fn($id)=>$id->toString(), $h->quarkIds())]));
    return $res->withHeader('Content-Type','application/json')->withStatus(201);
});

$app->get('/hadrons', function ($req, $res) use ($hadronRepo) {
    $all = $hadronRepo->findAll();
    $out = array_map(fn($h)=>['type'=>$h->type(),'quarkIds'=>array_map(fn($id)=>$id->toString(),$h->quarkIds())], $all);
    $res->getBody()->write(json_encode($out));
    return $res->withHeader('Content-Type','application/json');
});

$app->run();
