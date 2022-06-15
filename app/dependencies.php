<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Laudis\Neo4j\Basic\Driver;
use Laudis\Neo4j\Basic\Session;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        Session::class => function () {
            // tu dolje umjesto 'test' treba dodati generiranu sifru koju mirna ima doma na laptopu
            //$uri = $_ENV['neo4j+s://0df6d930.databases.neo4j.io'] ?? 'bolt://neo4j:lv5PdN16PMP3JdPnaRMImoYN7E-VgJrQAZyIL3NqnJs@localhost';
            return Driver::create($_ENV['NEO4J_URI'] ?? 'bolt://neo4j:test@localhost')->createSession();
            return Driver::create($uri)->createSession();
        },

        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
    ]);
};
