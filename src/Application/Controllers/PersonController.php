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
    private Session $session;

    public function __construct(Session $session) {
        $this->session = $session;
    }

    // ispis svih osoba
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

    // ispis jedne osobe prema id-u
    public function getPerson(Request $request, Response $response): Response {
        $query = $request->getQueryParams();
        $person = $this->session->run(<<<'CYPHER'
        MATCH (p:Person {id: $id})
        RETURN  p.firstName AS firstname,
                p.lastName AS lastName,
                p.familyName AS familyName,
                p.birthYear AS birthYear,
                p.gender AS gender
        CYPHER, $query);

        if ($person->isEmpty()) {
            throw new HttpNotFoundException($request, sprintf('Cannot find Person with id: %s', $query['id']));
        }

        $response->getBody()->write(json_encode($person->first(), JSON_THROW_ON_ERROR));

        return $response;
    }

    // kreiranje osobe prema atributima, generiranje id-ja
    public function createPerson(Request $request, Response $response): Response {
        $id = Uuid::generate();
        /** @var array */
        $parsedBody = $request->getParsedBody();
        $parsedBody['id'] = $id;

        $this->session->run(<<<'CYPHER'
        CREATE (p:Person {
            id: $id, 
            firstName: $firstName, 
            lastName: $lastName,
            familyName: $familyName,
            birthYear: $birthYear,
            gender: $gender
        })
        CYPHER, $parsedBody);

        return $response->withStatus(201)
            ->withHeader('Location', '/person?id=' . $id);
    }

    // brisanje osobe iz baze prema id-ju
    public function deletePerson(Request $request, Response $response): Response {
        $this->session->run(<<<'CYPHER'
        MATCH (p:Person {id: $id})
        DETACH DELETE p
        CYPHER, $request->getQueryParams());

        return $response->withStatus(204);
    }

    // izmjena podataka o osobi prema id-u
    public function modifyPerson(Request $request, Response $response): Response {
        $query = $request->getQueryParams();
        // match (person) set (property) return
        // ne znam kako napraviti izmjenu samo jednog propertyja zbog nacina na koji radi getQueryParams()
        // stoga bih ili mijenjala sve podatke, ili kreirala novi cvor, kopirala veze i obrisala stari cvor
        // prva opcija mi ima daleko vise smisla: u viewu napravimo formu #izmjena, korisnik promijeni sta hoce i posalje sve atribute nama u controller. onda controllerom updateamo "sve stupce"
        $this->session->run(<<<'CYPHER'
        MATCH (p:Person {id: $id})
        SET p.firstName = $firstName, 
            p.lastName = $lastName,
            p.familyName = $familyName,
            p.birthYear = $birthYear,
            p.gender = $gender
        CYPHER, $query);
    }
}
