<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 2019-03-04
 * Time: 01:28
 */

namespace Controllers;

use Interop\Container\ContainerInterface;
use Models\Collaborator;
use Models\CollaboratorArea;
use Models\CollaboratorAvailable;
use Models\Person;
use Slim\Http\Request;
use Slim\Http\Response;

class CollaboratorsPostController
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
        if ((!array_key_exists('cpc-g-recaptcha-response', $body)) || (empty($body['cpc-g-recaptcha-response']))) {
            return $response->withJson([
                'message' => 'O campo Captcha é obrigatório.'
            ], 400);
        }

        $captcha = $body['cpc-g-recaptcha-response'];
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

        $disponibilidades = [];
        $areasColaboracao = [];

        $person = new Person();
        $collaborator = new Collaborator();

        if ((!array_key_exists('cpc-nome', $body)) || (empty($body['cpc-nome']))) {
            return $response->withJson([
                'message' => 'O campo Nome é obrigatório.'
            ], 400);
        } else {
            $person->name = $body['cpc-nome'];
        }

        if ((!array_key_exists('cpc-email', $body)) || (empty($body['cpc-email']))) {
            return $response->withJson([
                'message' => 'O campo E-mail é obrigatório.'
            ], 400);
        } else {
            $person->email = $body['cpc-email'];
        }

        if ((!array_key_exists('cpc-telefone', $body)) || (empty($body['cpc-telefone']))) {
            return $response->withJson([
                'message' => 'O campo Telefone é obrigatório.'
            ], 400);
        } else {
            $person->phone = $body['cpc-telefone'];
        }

//        if ((!array_key_exists('cpc-usa-software-livre', $body)) || (($body['cpc-usa-software-livre'] !== 0) && ($body['cpc-usa-software-livre'] !== 1))) {
//            return $response->withJson([
//                'message' => 'O campo Usa Software Livre? é obrigatório.'
//            ], 400);
//        } else {
//            $person->use_free = $body['cpc-usa-software-livre'];
//        }
        $person->use_free = $body['cpc-usa-software-livre'];

        if ((!array_key_exists('cpc-distribuicao', $body)) || (empty($body['cpc-distribuicao']))) {
            return $response->withJson([
                'message' => 'O campo Qual distribuição GNU/Linux você usa? é obrigatório.'
            ], 400);
        } else {
            $person->distro_id = $body['cpc-distribuicao'];
        }

        if ((!array_key_exists('cpc-estudante', $body)) || (empty($body['cpc-estudante']))) {
            return $response->withJson([
                'message' => 'O campo Você é estudante? é obrigatório.'
            ], 400);
        } else {
            $person->student_info_id = $body['cpc-estudante'];
        }

        if ((!array_key_exists('cpc-local-estudo-trabalho', $body)) || (empty($body['cpc-local-estudo-trabalho']))) {
            return $response->withJson([
                'message' => 'O campo Onde você estuda ou trabalha? é obrigatório.'
            ], 400);
        } else {
            $person->student_place = $body['cpc-local-estudo-trabalho'];
        }

        if ((array_key_exists('cpc-disponibilidade', $body)) && (is_array($body['cpc-disponibilidade']))) {
            foreach ($body['cpc-disponibilidade'] as $cpcDisponibilidade) {
                if (((int)$cpcDisponibilidade) > 0) {
                    $disponibilidades[] = (int)$cpcDisponibilidade;
                }
            }
        }

        if (count($disponibilidades) === 0) {
            return $response->withJson([
                'message' => 'O campo Qual a sua disponibilidade de tempo? é obrigatório.'
            ], 400);
        }

        if ((array_key_exists('cpc-area-colaboracao', $body)) && (is_array($body['cpc-area-colaboracao']))) {
            foreach ($body['cpc-area-colaboracao'] as $cpcAreaColaboracao) {
                if (((int)$cpcAreaColaboracao) > 0) {
                    $areasColaboracao[] = (int)$cpcAreaColaboracao;
                }
            }
        }

        if (count($areasColaboracao) === 0) {
            return $response->withJson([
                'message' => 'O campo Onde deseja colaborar? é obrigatório.'
            ], 400);
        }

        $person->save();

        // DONE: Turn it dynamic from table edition
        $collaborator->edition_id = $settings['edition'];
        $collaborator->person_id = $person->id;
        $collaborator->save();

        foreach ($disponibilidades as $disponibilidade) {
            $collaboratorAvaiable = new CollaboratorAvailable();
            $collaboratorAvaiable->collaborator_id = $collaborator->id;
            $collaboratorAvaiable->collaborator_availability_id = $disponibilidade;
            $collaboratorAvaiable->save();
        }

        foreach ($areasColaboracao as $areaColaboracao) {
            $collaboratorArea = new CollaboratorArea();
            $collaboratorArea->collaborator_id = $collaborator->id;
            $collaboratorArea->collaboration_area_id = $areaColaboracao;
            $collaboratorArea->save();
        }

        return $response->withJson([
            'message' => 'O seu pedido foi enviado com sucesso. Aguarde o nosso retorno.'
        ], 200);
    }

}