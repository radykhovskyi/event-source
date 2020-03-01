<?php

use App\Actions\CreateLampAction;
use App\Actions\GetLampAction;
use App\Actions\UpdateLampAction;
use App\Commands\GetLampQuery;
use App\Commands\InstallLampCommand;
use App\Commands\UpdateLampCommand;
use App\Handlers\GetLampHandler;
use App\Handlers\InstallLampHandler;
use App\Handlers\UpdateLampHandler;
use App\Http\Middlewares\JsonBodyParserMiddleware;
use App\Models\Lamp;
use Doctrine\DBAL\DriverManager;
use EventSauce\EventSourcing\ConstructingAggregateRootRepository;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_error_handler(
    function ($severity, $message, $file, $line) {
        throw new ErrorException($message, $severity, $severity, $file, $line);
    }
);
set_exception_handler(fn (Throwable $exception) => dd($exception));

$container = new \Pimple\Container();
AppFactory::setContainer(new \Pimple\PSR11\Container($container));
$app = AppFactory::create();
$app->addMiddleware(new JsonBodyParserMiddleware());

$config = require_once __DIR__ . '/config/app.php';

$messageRepository = new EventSauce\DoctrineMessageRepository\DoctrineMessageRepository(
    DriverManager::getConnection([
        'dbname' => $config['db']['database'],
        'user' => $config['db']['username'],
        'password' => $config['db']['password'],
        'host' => $config['db']['host'],
        'driver' => 'pdo_mysql',
        'strict' => false,
    ]),
    new ConstructingMessageSerializer(),
    'event_log'
);
$repository = new ConstructingAggregateRootRepository(
    Lamp::class,
    $messageRepository
);
$commandBus = League\Tactician\Setup\QuickStart::create(
    [
        // commands
        InstallLampCommand::class => new InstallLampHandler($repository),
        UpdateLampCommand::class => new UpdateLampHandler($repository),
        // queries
        GetLampQuery::class => new GetLampHandler($repository),
    ]
);
$container['command_bus'] = $commandBus;
$container['repository'] = $repository;

$app->post('/lamps', CreateLampAction::class);
$app->patch('/lamps/{id}', UpdateLampAction::class);
$app->get('/lamps/{id}', GetLampAction::class);

$app->run();