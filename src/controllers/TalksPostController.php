<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 2019-03-04
 * Time: 01:28
 */

namespace Controllers;

use Exception;
use Interop\Container\ContainerInterface;
use Models\Person;
use Models\SpeakerTalk;
use Models\Talk;
use Slim\Http\Request;
use Slim\Http\Response;

class TalksPostController extends UploaderController
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
        if ((!array_key_exists('pl-g-recaptcha-response', $body)) || (empty($body['pl-g-recaptcha-response']))) {
            return $response->withJson([
                'message' => 'O campo Captcha é obrigatório.'
            ], 400);
        }

        $captcha = $body['pl-g-recaptcha-response'];
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
        $talk = new Talk();
        $speakerTalk = new SpeakerTalk();

        $personDir = $settings['uploads']['person'];
        if (!file_exists($personDir)) {
            mkdir($personDir, 0755, true);
        }

        $talkDir = $settings['uploads']['talk'];
        if (!file_exists($talkDir)) {
            mkdir($talkDir, 0755, true);
        }

        $uploadedFiles = $request->getUploadedFiles();

        // TODO: Permitir múltiplos palestrantes e múltiplas palestras

        if ((!array_key_exists('pl-nome', $body)) || (empty($body['pl-nome']))) {
            return $response->withJson([
                'message' => 'O campo Nome é obrigatório.'
            ], 400);
        } else {
            $person->name = $body['pl-nome'];
        }

        if ((!array_key_exists('pl-email', $body)) || (empty($body['pl-email']))) {
            return $response->withJson([
                'message' => 'O campo E-mail é obrigatório.'
            ], 400);
        } else {
            $person->email = $body['pl-email'];
        }

        if ((!array_key_exists('pl-telefone', $body)) || (empty($body['pl-telefone']))) {
            return $response->withJson([
                'message' => 'O campo Telefone é obrigatório.'
            ], 400);
        } else {
            $person->phone = $body['pl-telefone'];
        }

        if (!array_key_exists('pl-foto', $uploadedFiles)) {
            return $response->withJson([
                'message' => 'O campo Foto é obrigatório.'
            ], 400);
        } else {
            // Verify is file is sent
            try {
                $this->fileIsSent($uploadedFiles['pl-foto']);
            } catch (Exception $e) {
                return $response->withJson([
                    'message' => 'O campo Foto é obrigatório.'
                ], 400);
            }
        }

        if ((!array_key_exists('pl-minicurriculo', $body)) || (empty($body['pl-minicurriculo']))) {
            return $response->withJson([
                'message' => 'O campo Minicurrículo é obrigatório.'
            ], 400);
        } else {
            $person->bio = $body['pl-minicurriculo'];
        }

        if ((array_key_exists('pl-site', $body)) && (!empty($body['pl-site']))) {
            $person->site = $body['pl-site'];
        }

        if ((!array_key_exists('pl-titulo', $body)) || (empty($body['pl-titulo']))) {
            return $response->withJson([
                'message' => 'O campo Título é obrigatório.'
            ], 400);
        } else {
            $talk->title = $body['pl-titulo'];
        }

        if ((!array_key_exists('pl-descricao', $body)) || (empty($body['pl-descricao']))) {
            return $response->withJson([
                'message' => 'O campo Descrição é obrigatório.'
            ], 400);
        } else {
            $talk->description = $body['pl-descricao'];
        }

        if ((!array_key_exists('pl-turno', $body)) || (empty($body['pl-turno']))) {
            return $response->withJson([
                'message' => 'O campo Turno é obrigatório.'
            ], 400);
        } else {
            $talk->shift = $body['pl-turno'];
        }

        if ((!array_key_exists('pl-tipo', $body)) || (empty($body['pl-tipo']))) {
            return $response->withJson([
                'message' => 'O campo Tipo é obrigatório.'
            ], 400);
        } else {
            $talk->kind = $body['pl-tipo'];
        }

        if ((!array_key_exists('pl-assunto', $body)) || (empty($body['pl-assunto']))) {
            return $response->withJson([
                'message' => 'O campo Assunto é obrigatório.'
            ], 400);
        } else {
            $talk->talk_subject_id = $body['pl-assunto'];
        }

        if ((array_key_exists('pl-slide-url', $body)) && (!empty($body['pl-slide-url']))) {
            $talk->slide_url = $body['pl-slide-url'];
        }

        $person->save();
        $speakerTalk->speaker_id = $person->id;

        try {
            // Move photo to right place
            $personFileDir = $personDir . '/' . $person->id;

            if (!file_exists($personFileDir)) {
                mkdir($personFileDir, 0755, true);
            }

            $person->photo = $this->moveUploadedFile($personFileDir, $uploadedFiles['pl-foto']);
            $person->save();
        } catch (Exception $e) {
            return $response->withJson([
                'message' => 'O campo Foto é obrigatório.'
            ], 400);
        }

        // DONE: Turn it dynamic from table edition
        $talk->edition_id = $settings['edition'];
        $talk->save();
        $speakerTalk->talk_id = $talk->id;

        try {
            // Move slide to right place
            if (array_key_exists('pl-slide', $uploadedFiles)) {

                // Verify is file is sent
                if ($this->fileIsSent($uploadedFiles['pl-slide'])) {
                    $talkFileDir = $talkDir . '/' . $talk->id;

                    if (!file_exists($talkFileDir)) {
                        mkdir($talkFileDir, 0755, true);
                    }

                    $talk->slide_file = $this->moveUploadedFile($talkFileDir, $uploadedFiles['pl-slide']);
                    $talk->save();
                }
            }
        } catch (Exception $e) {
            // Do nothing.
        }

        $speakerTalk->save();

        return $response->withJson([
            'message' => 'A sua palestra foi submetida com sucesso. Aguarde o nosso retorno.'
        ], 200);
    }

}