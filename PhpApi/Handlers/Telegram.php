<?php

namespace PhpApi\Handlers;

use PhpApi\Core\Config;
use PhpApi\Core\Helpers\Handler;
use PhpApi\Core\Functions\Request;
use PhpApi\Core\Functions\Response;
use PhpApi\Middlewares\IsTelegram;
use PhpApi\Services\TelegramBot;
use PhpApi\Jobs\VideoJob;

class Telegram extends Handler
{

    /**
     * index method
     *
     * @param Request $req the current request
     * @param Response $res the current response
     */
    public function Index(Request $req, Response $res)
    {
        (new IsTelegram())->intercept($req, $res);
        
        $payload = $req->json;
        $res->end();

        /* terminate if invalid payload */
        if (empty($payload)) return;
        
        $tg = (new Config())->keys()->telegram;
        $message = TelegramBot::getMessage($payload);

        /* echo message */
        $reply = $message->text ?? "Hello, {$message->user->firstname}!";
        (new TelegramBot())
            ->setToken($tg->token)
            ->setChatId($message->chat_id)
            ->sendMessage($reply);
    }
}
