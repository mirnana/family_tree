<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Controllers\PersonController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response->withHeader('Content-Type', 'text/plain');;
    });

    
    $app->get('/persons', [PersonController::class, 'listPersons']);
    $app->get('/person', [PersonController::class, 'getPerson']);
    $app->post('/person', [PersonController::class, 'createPerson']);
    $app->post('/modifyperson', [PersonController::class, 'modifyPerson']); // vidjeti kako radi ovaj header
    $app->delete('/person', [PersonController::class, 'deletePerson']);

    // za view možemo nešto tipa:
    /*$app->group('/persons', function() use ($app) {
        $app->post('/nešto', [new PersonController(), 'createPerson']);
    });*/
};
