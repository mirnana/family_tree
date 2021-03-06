<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Controllers\PersonController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Views\PhpRenderer;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    // primjer kako dati viewu argumente koje mozemo koristiti u html templejtu:
    /*$app->get('/', function (Request $request, Response $response) {
        $renderer = new PhpRenderer("../views"); // to je putanja do viewova
        $args = ['mirnakey' => 'mirna'];
        return $renderer->render($response, "hello.html", $args);
    });
    */
    $app->get('/persons', function(Request $request, Response $response) use ($app) {
        $app->get('/persons', [PersonController::class, 'listPersons']);
        $args = ['response' => $response];
        $renderer = new PhpRenderer("../views"); // to je putanja do viewova
        return $renderer->render($response, "hello.html", $args);

    });

    /*
    $app->get('/person', [PersonController::class, 'listPersons']);
    $app->get('/person/getone', [PersonController::class, 'getPerson']);
    $app->post('/person', [PersonController::class, 'createPerson']);
    $app->post('/person/modify', [PersonController::class, 'modifyPerson']); // vidjeti kako radi ovaj header
    $app->delete('/person', [PersonController::class, 'deletePerson']);
    */

    // za view možemo nešto tipa:
    /*$app->group('/persons', function() use ($app) {
        $app->post('/nešto', [new PersonController(), 'createPerson']);


        $renderer = new PhpRenderer("../views"); // to je putanja do viewova
        return $renderer->render($response, "nesto.html", $args);

        itd
    });*/
};
