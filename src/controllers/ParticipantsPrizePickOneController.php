<?php

namespace Controllers;

use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ParticipantsPrizePickOneController
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $args = [])
    {
        // Create connection. Necessary to stablish a connection.
        $db = $this->container->get('db');

        $participants = $db::select('SELECT id, name, email FROM participant WHERE (NOT prizedraw_confirmation_at IS NULL) AND (prizedraw_winner IS NULL) ORDER BY RAND() LIMIT 1');

        return $response->withJson([
            'participant' => [
                'id' => $participants[0]->id,
                'name' => $participants[0]->name,
                'email' => $participants[0]->email
            ]
        ], 200);
    }

}