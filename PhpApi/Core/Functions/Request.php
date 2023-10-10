<?php

namespace PhpApi\Core\Functions;

/**
 * the request class.
 * Once initialized it automatically collects all the relevent information pertaining to the
 * request at the time of initialization.
 */
class Request
{

    /**
     * @var object $route the request route
     */
    public $route;

    /**
     * @var string $method the request method
     */
    public $method;

    /**
     * @var object $headers the request headers
     */
    public $headers;

    /**
     * @var object $params a property containing all parameters associated with the request
     */
    public $params;

    /**
     * @var array $query a property containing url associated query.
     */
    public $query;

    /**
     * @var object $body a property containing all request body data. 
     */
    public $body;

    /**
     * @var object $files a property containing all request uploaded files. 
     */
    public $files;

    /**
     * @var array $json
     */
    public $json;

    /**
     * default constructor
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * handles information pertaining to the current request
     */
    private function init()
    {
        /** method */
        $this->method = $_SERVER['REQUEST_METHOD'];

        /** route */
        $this->route = (object)[
            'method' => $_SERVER['REQUEST_METHOD'],
            'uri' => $_SERVER['REDIRECT_URL'],
        ];

        /** headers */
        $this->headers = (object)array_flip((array_map(function ($hKey) {
            return strtolower(str_replace('-', '_', $hKey));
        },  array_flip(getallheaders()))));

        /** query */
        if (strlen($_SERVER['QUERY_STRING']) > 0) {
            $params = explode('&', $_SERVER['QUERY_STRING']);
            foreach ($params as $param) {
                $queryParam = explode('=', $param);
                $this->query[$queryParam[0]] = $queryParam[1];
            }
        }

        /** json */
        $json = file_get_contents('php://input');
        $json = json_decode($json, true);

        $this->query = (object)($this->query);
        $this->body = (object)$_POST;
        $this->files = (object)$_FILES;
        $this->json = $json;
    }
}
