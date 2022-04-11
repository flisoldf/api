<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 2019-03-10
 * Time: 01:06
 */

namespace Controllers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class BotController
{

    private $apiUrl = '';
    private $chatId = '';

    protected function unknown()
    {
        return $this->sendMessage('Comando desconhecido. Tente /help para ver a lista de comandos.');
    }

    /**
     * @param $message
     * @return bool
     * @throws Exception
     */
    protected function sendMessage($message)
    {
        $client = new Client();

        if (is_string($message)) {
            $text = trim($message);

            if (strlen(trim($text)) > 0) {
                $response = $client->post($this->apiUrl . '/sendmessage', [
                    RequestOptions::JSON => [
                        'chat_id' => $this->chatId,
                        'parse_mode' => 'html',
                        'text' => $text,
                    ],
                ]);

//                if ($response->getStatusCode() !== 200) {
//                    throw new \Exception($response->getBody()->getContents());
//                }

                return true;
            }
        }

        return false;
    }

    protected function init($botToken, $chatId)
    {
        $this->apiUrl = 'https://api.telegram.org/bot' . $botToken;
        $this->chatId = $chatId;
    }

}