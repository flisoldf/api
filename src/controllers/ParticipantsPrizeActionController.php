<?php

namespace Controllers;

use DateTime;
use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ParticipantsPrizeActionController
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

        $id = null;
        $action = null;
        $prize = null;
        $order = null;

        if ((!array_key_exists('id', $body)) || (empty($body['id']))) {
            return $response->withJson([
                'message' => 'O campo Identificação é obrigatório.'
            ], 400);
        } else {
            $id = $body['id'];
        }

        if ((!array_key_exists('action', $body)) || (empty($body['action']))) {
            return $response->withJson([
                'message' => 'O campo Ação é obrigatório.'
            ], 400);
        } else {
            $action = $body['action'];
        }

        if ((!array_key_exists('prize', $body)) || (empty($body['prize']))) {
            $prize = null;
        } else {
            $prize = $body['prize'];
        }

        if ((!array_key_exists('order', $body)) || (empty($body['order']))) {
            $order = null;
        } else {
            $order = $body['order'];
        }

        if ($action == 'add') {
            $db::table('participant')
                ->where('id', $id)
                ->update([
                    'prizedraw_winner' => new DateTime(),
                    'prizedraw_description' => $prize,
                    'prizedraw_order' => $order
                ]);
        } elseif ($action == 'remove') {
            $db::table('participant')
                ->where('id', $id)
                ->update(['prizedraw_confirmation_at' => null]);
        }

        return $response->withJson([
            'message' => 'Ação realizada com sucesso.'
        ], 200);
    }

}