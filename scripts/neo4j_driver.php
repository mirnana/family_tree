<?php

use Dotenv\Dotenv;
use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\Basic\Driver;
use Laudis\Neo4j\Databags\DriverConfiguration;

require __DIR__ . '/../vendor/autoload.php';

Dotenv::createImmutable(__DIR__ . '/../')->safeLoad();

// driver za našu bazu na Neo4j Aura serveru
//$driver = Driver::create($_ENV['neo4j+s://0df6d930.databases.neo4j.io']);
$driver = Driver::create($_ENV['NEO4J_URI']);

// tu dolje umjesto 'test' treba dodati generiranu sifru koju mirna ima doma na laptopu
//$boltDriver = Driver::create('bolt://neo4j:lv5PdN16PMP3JdPnaRMImoYN7E-VgJrQAZyIL3NqnJs@localhost');
$boltDriver = Driver::create('bolt://neo4j:test@localhost');

/*Creates an auto routed driver with credentials neo4j and password test with the custom port 7777 ~~~ vidjeti što je sa šifrom*/
// kad se ubaci sifra u uri, onda javlja syntax error ??????
$neo4jDriver = Driver::create(uri: 'neo4j:test//localhost:7777', authenticate: Authenticate::disabled());

// tu dolje umjesto 'test' treba dodati generiranu sifru koju mirna ima doma na laptopu
//$http = Driver::create(uri: 'http://localhost?database=family_tree', authenticate: Authenticate::basic('neo4j', 'lv5PdN16PMP3JdPnaRMImoYN7E-VgJrQAZyIL3NqnJs'));
$http = Driver::create(uri: 'http://localhost?database=family_tree', authenticate: Authenticate::basic('neo4j', 'test'));

/* Creates a bolt driver with disabled authentication on default port 7687 and with custom user agent MyAmazingApp 9000 ~~~ sta je user agent lol*/
$oidcDriver = Driver::create(
    uri: 'bolt://localhost',
    configuration: DriverConfiguration::default()->withUserAgent('MyAmazingApp/9000'),
    authenticate: Authenticate::disabled()
);

echo 'Created driver' . PHP_EOL;
$session = $driver->createSession();

echo 'Created session' . PHP_EOL;
if ($driver->verifyConnectivity()) {
    echo 'Can connect to database' . PHP_EOL;
} else {
    echo 'Cannot connect to database' . PHP_EOL;
}