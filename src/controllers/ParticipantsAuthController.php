<?php

namespace Controllers;

use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ParticipantsAuthController
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
        $body = $request->getParsedBody();

        // Create connection. Necessary to stablish a connection.
        $db = $this->container->get('db');

        $email = null;
        $federalCode = null;
        $codigoConfirmacao = null;

        if ((!array_key_exists('email', $body)) || (empty($body['email']))) {
            return $response->withJson([
                'message' => 'O campo E-mail é obrigatório.'
            ], 400);
        } else {
            $email = $body['email'];
        }

        if ((!array_key_exists('federalCode', $body)) || (empty($body['federalCode']))) {
            return $response->withJson([
                'message' => 'O campo CPF é obrigatório.'
            ], 400);
        } else {
            $federalCode = $body['federalCode'];
        }

        $participant = $db::table('participant')->where([
            ['email', '=', $email],
            ['federal_code', '=', $federalCode],
        ])->first();

        return $response->withJson([
            'auth' => !is_null($participant)
        ], 200);
    }

}