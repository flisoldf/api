<?php

namespace Controllers;

use DateTime;
use DateTimeZone;
use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ParticipantsPresenceVerifyController
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

        $dateNow = new DateTime();
        $dateNow->setTimezone(new DateTimeZone('America/Sao_Paulo'));

        $dateLimit = new DateTime('2019-04-27 16:00:00', new DateTimeZone('America/Sao_Paulo'));

        if ($dateNow > $dateLimit) {
            $participant = $db::table('participant')->where([
                ['email', '=', $email],
                ['federal_code', '=', $federalCode],
            ])->first();

            if (is_null($participant)) {
                return $response->withJson([
                    'message' => 'Seu cadastro não foi encontrado.',
                    'found' => false
                ], 400);
            } elseif (!is_null($participant->prizedraw_confirmation_at)) {
                return $response->withJson([
                    'message' => 'Sua presença já está confirmada. Boa sorte!',
                    'found' => true,
                    'confirmed' => true
                ], 200);
            } else {
                return $response->withJson([
                    'message' => 'Você está autorizado a confirmar a presença.',
                    'found' => true,
                    'confirmed' => false
                ], 200);
            }
        } else {
            return $response->withJson([
                'message' => 'Uh Oh. Ainda não são 16:00h.'
            ], 400);
        }
    }

}