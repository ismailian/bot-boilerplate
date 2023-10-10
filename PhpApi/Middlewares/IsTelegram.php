<?php

namespace PhpApi\Middlewares;

use PhpApi\Core\Config;
use PhpApi\Core\Functions\Request;
use PhpApi\Core\Functions\Response;
use PhpApi\Core\Helpers\Middleware;

class IsTelegram extends Middleware
{

    /**
     * apply middleware to the context request.
     *
     * @param Request $req the content request.
     */
    public function intercept(Request $req, Response $res)
    {
        if (!isset($req->headers->x_telegram_bot_api_secret_token)) {
            return $res->status(401)->json([
                'error' => 401,
                'message' => '401 Unauthorized'
            ]);
        }

        $secret = $req->headers->x_telegram_bot_api_secret_token;
        if (!hash_equals($secret, (new Config())->keys()->telegram->signature)) {
            return $res->status(401)->json([
                'error' => 401,
                'message' => 'invalid signature'
            ]);
        }
    }
}