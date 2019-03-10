<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 2019-03-10
 * Time: 01:06
 */

namespace Controllers;


class BotController
{

    private $apiUrl = '';
    private $chatId = '';

    protected function unknown()
    {
        return $this->send('Comando desconhecido. Tente /ajuda para ver a lista de comandos.');
    }

    protected function send($message)
    {
        $text = trim($message);

        if (strlen(trim($text)) > 0) {
            $send = $this->apiUrl . '/sendmessage?parse_mode=html&chat_id=' . $this->chatId . '&text=' . urlencode($text);
            file_get_contents($send);

            return true;
        }

        return false;
    }

    protected function init($botToken, $chatId)
    {
        $this->apiUrl = 'https://api.telegram.org/bot' . $botToken;
        $this->chatId = $chatId;
    }

}