<?php

namespace App\Application\Controllers;

use App\Domain\Uuid;
use Laudis\Neo4j\Basic\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use function json_encode;

// pozabaviti se cypher upitima!
final class PersonController {
    public function __construct(private Session $session) {
    }

    public function listPersons(Request $request, Response $response): Response {
        $persons = $this->session->run(<<<'CYPHER'
        MATCH (p:Person)
        RETURN  p.firstName AS firstname,
                p.lastName AS lastName,
                p.familyName AS familyName,
                p.birthYear AS birthYear,
                p.gender AS gender
        CYPHER);

        $response->getBody()->write(json_encode($persons->getResults(), JSON_THROW_ON_ERROR));

        return $response;
    }

    // ova fja nam ne bi smjela trebati al nek stoji:
    public function getPerson(Request $request, Response $response): Response {
        $query = $request->getQueryParams();
        $person = $this->session->run(<<<'CYPHER'
        MATCH (u:Person {id: $id})
        RETURN  u.firstName AS firstname,
                u.secondName AS secondName,
                u.id AS id
        CYPHER, $query);

        if ($person->isEmpty()) {
            throw new HttpNotFoundException($request, sprintf('Cannot find Person with id: %s', $query['id']));
        }

        $response->getBody()->write(json_encode($person->first(), JSON_THROW_ON_ERROR));

        return $response;
    }

    public function createPerson(Request $request, Response $response): Response {
        $id = Uuid::generate();
        /** @var array */
        $parsedBody = $request->getParsedBody();
        $parsedBody['id'] = $id;

        $this->session->run(<<<'CYPHER'
        CREATE (u:Person {id: $id, firstName: $firstName, secondName: $secondName})
        CYPHER, $parsedBody);

        return $response->withStatus(201)
            ->withHeader('Location', '/person?id=' . $id);
    }

    public function deletePerson(Request $request, Response $response): Response {
        $this->session->run(<<<'CYPHER'
        MATCH (u:Person {id: $id})
        DETACH DELETE u
        CYPHER, $request->getQueryParams());

        return $response->withStatus(204);
    }

    public function modifyPerson(Request $request, Response $response): Response {
        $query = $request->getQueryParams();
        // match (person) set (property) return
    }
}

/* nisam shvatila sto ovo znaci: By adding the session as a parameter in the controllers’ constructor, we automatically tell the application to inject it. By defining a Session key in the dependencies’ definition, we provided enough information to the application to successfully inject the parameter of this type.*/