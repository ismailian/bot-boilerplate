<?php

namespace PhpApi\Middlewares;

use PhpApi\Core\Config;
use PhpApi\Core\Functions\Request;
use PhpApi\Core\Functions\Response;
use PhpApi\Core\Helpers\Middleware;

class Authorization extends Middleware
{

    /**
     * apply middleware to the context request. 
     *
     * @param Request $req the content request. 
     */
    public function intercept(Request $req, Response $res)
    {
        $key = $req->query->key ?? null;
        $api_key = (new Config)->keys()->auth->api_key;

        /** verify token */
        if (empty($key) || !hash_equals($key, $api_key)) {
            $res->status(401)->json([
                'status' => false,
                'error' => '401 Unauthorized'
            ]);
        }
    }
}
