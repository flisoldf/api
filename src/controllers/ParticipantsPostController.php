<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 2022-04-06
 * Time: 00:15
 */

namespace Controllers;

use Interop\Container\ContainerInterface;
use Models\Participant;
use Models\Person;
use Slim\Http\Request;
use Slim\Http\Response;

class ParticipantsPostController
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

        $settings = $this->container->get('settings');

        /*
        // Captcha validation
        if ((!array_key_exists('sup-g-recaptcha-response', $body)) || (empty($body['sup-g-recaptcha-response']))) {
            return $response->withJson([
                'message' => 'O campo Captcha é obrigatório.'
            ], 400);
        }

        $captcha = $body['sup-g-recaptcha-response'];
        $secret = $settings['catpcha']['secretKey'];
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
        $captchaResponse = file_get_contents($url);
        // Use json_decode to extract json response
        $captchaResponse = json_decode($captchaResponse);

        // Ex: {"success": false, "error-codes": ["timeout-or-duplicate"]}
        if ($captchaResponse->success === false) {
            return $response->withJson([
                'message' => 'O valor do campo Captcha não é válido.'
            ], 400);
        }

        //... The Captcha is invalid you can't continue with the rest of your code
        //... Add code to filter access using $response . score
        if (($captchaResponse->success == true) && ($captchaResponse->score <= 0.5)) {
            return $response->withJson([
                'message' => 'O valor do campo Captcha não possui um score alto.'
            ], 400);
        }
        */

        // Create connection. Necessary to establish a connection.
        $db = $this->container->get('db');

        $person = new Person();
        $participant = new Participant();

        if ((!array_key_exists('sup-nome', $body)) || (empty($body['sup-nome']))) {
            return $response->withJson([
                'message' => 'O campo Nome é obrigatório.'
            ], 400);
        } else {
            $person->name = $body['sup-nome'];
        }

        if ((!array_key_exists('sup-cpf', $body)) || (empty($body['sup-cpf']))) {
            return $response->withJson([
                'message' => 'O campo CPF é obrigatório.'
            ], 400);
        } else {
            $person->federal_code = $body['sup-cpf'];
        }

        if ((!array_key_exists('sup-email', $body)) || (empty($body['sup-email']))) {
            return $response->withJson([
                'message' => 'O campo E-mail é obrigatório.'
            ], 400);
        } else {
            $person->email = $body['sup-email'];
        }

        if ((!array_key_exists('sup-telefone', $body)) || (empty($body['sup-telefone']))) {
            return $response->withJson([
                'message' => 'O campo Telefone é obrigatório.'
            ], 400);
        } else {
            $person->phone = $body['sup-telefone'];
        }

//        if ((!array_key_exists('sup-usa-software-livre', $body)) || (($body['sup-usa-software-livre'] !== 0) && ($body['sup-usa-software-livre'] !== 1))) {
//            return $response->withJson([
//                'message' => 'O campo Usa Software Livre? é obrigatório.'
//            ], 400);
//        } else {
//            $person->use_free = $body['sup-usa-software-livre'];
//        }
        $person->use_free = $body['sup-usa-software-livre'];

        if ((!array_key_exists('sup-distribuicao', $body)) || (empty($body['sup-distribuicao']))) {
            return $response->withJson([
                'message' => 'O campo Qual distribuição GNU/Linux você usa? é obrigatório.'
            ], 400);
        } else {
            $person->distro_id = $body['sup-distribuicao'];
        }

        if ((!array_key_exists('sup-estudante', $body)) || (empty($body['sup-estudante']))) {
            return $response->withJson([
                'message' => 'O campo Você é estudante? é obrigatório.'
            ], 400);
        } else {
            $person->student_info_id = $body['sup-estudante'];
        }

        if ((!array_key_exists('sup-local-estudo-trabalho', $body)) || (empty($body['sup-local-estudo-trabalho']))) {
            return $response->withJson([
                'message' => 'O campo Onde você estuda ou trabalha? é obrigatório.'
            ], 400);
        } else {
            $person->student_place = $body['sup-local-estudo-trabalho'];
        }

        if ((!array_key_exists('sup-curso', $body)) || (empty($body['sup-curso']))) {
            return $response->withJson([
                'message' => 'O campo Qual curso faz ou é formado? é obrigatório.'
            ], 400);
        } else {
            $person->student_course = $body['sup-curso'];
        }

        if ((!array_key_exists('sup-uf', $body)) || (empty($body['sup-uf']))) {
            return $response->withJson([
                'message' => 'O campo Qual o estado você reside? é obrigatório.'
            ], 400);
        } else {
            $person->address_state = $body['sup-uf'];
        }

        $person->save();

        // DONE: Turn it dynamic from table edition
        $participant->edition_id = $settings['edition'];
        $participant->person_id = $person->id;
        $participant->save();

        return $response->withJson([
            'message' => 'O sua inscrição foi realizada com sucesso. Até breve!'
        ], 200);
    }

}