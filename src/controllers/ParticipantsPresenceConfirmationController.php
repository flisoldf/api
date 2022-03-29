<?php


namespace Controllers;


use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ParticipantsPresenceConfirmationController
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $args = [])
    {
        $body = $request->getParsedBody();

        // Create connection. Necessary to stablish a connection.
        $db = $this->container->get('db');

        $participantDir = $this->container->get('settings')['uploads']['participant'];
        if (!file_exists($participantDir)) {
            mkdir($participantDir, 0755, true);
        }

        $email = null;
        $federalCode = null;
        $confirmationCode = null;
        $photo = null;

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

        if ((!array_key_exists('photo', $body)) || (empty($body['photo']))) {
            return $response->withJson([
                'message' => 'O campo Foto é obrigatório.'
            ], 400);
        } else {
            $photo = $body['photo'];
        }

        if ((!array_key_exists('confirmationCode', $body)) || (empty($body['confirmationCode']))) {
            return $response->withJson([
                'message' => 'O campo Código de Confirmação é obrigatório.'
            ], 400);
        } else {
            $confirmationCode = $body['confirmationCode'];
        }

        $dateNow = new \DateTime();
        $dateNow->setTimezone(new \DateTimeZone('America/Sao_Paulo'));

        $dateLimit = new \DateTime('2019-04-27 16:00:00', new \DateTimeZone('America/Sao_Paulo'));

        if ($confirmationCode !== '~3?<?gdZuHHY,8y?n4bQ,>LDR&&!RaEm') {
            return $response->withJson([
                'message' => 'O código de confirmação não confere.'
            ], 400);
        } elseif ($dateNow > $dateLimit) {
            $participant = $db::table('participant')->where([
                ['email', '=', $email],
                ['federal_code', '=', $federalCode],
            ])->first();

            if (is_null($participant)) {
                return $response->withJson([
                    'message' => 'Seu cadastro não foi encontrado.'
                ], 400);
            } elseif (is_null($participant->prizedraw_confirmation_at)) {
                $db::table('participant')
                    ->where('id', $participant->id)
                    ->update(['prizedraw_confirmation_at' => new \DateTime()]);

                // Move photo to right place
                $participantFileDir = $participantDir . '/' . $participant->id;
                if (!file_exists($participantFileDir)) {
                    mkdir($participantFileDir, 0755, true);
                }

                // Save the photo
                $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
                $filename = $participantFileDir . DIRECTORY_SEPARATOR . sprintf('%s.%0.8s', $basename, 'jpg');

                $imageData = base64_decode($photo);
                $source = imagecreatefromstring($imageData);
//                $angle = 90;
//                $rotate = imagerotate($source, $angle, 0); // if want to rotate the image
//                $imageSave = imagejpeg($rotate, $imageName,100);
                imagejpeg($source, $filename, 100);
                imagedestroy($source);

                return $response->withJson([
                    'message' => 'Sua presença está confirmada. Boa sorte!'
                ], 200);
            } else {
                return $response->withJson([
                    'message' => 'Sua presença já está confirmada. Boa sorte!'
                ], 200);
            }
        } else {
            return $response->withJson([
                'message' => 'Uh Oh. Ainda não são 16:00h.'
            ], 400);
        }
    }

}