<?php

namespace PhpApi\Core\Functions;

class Request
{

    /**
     * @var object $route the request route
     */
    public object $route;

    /**
     * @var string $method the request method
     */
    public string $method;

    /**
     * @var object $headers the request headers
     */
    public object $headers;

    /**
     * @var object $params a property containing all parameters associated with the request
     */
    public object $params;

    /**
     * @var object $query a property containing url associated query.
     */
    public object $query;

    /**
     * @var object $body a property containing all request body data. 
     */
    public object $body;

    /**
     * @var object $files a property containing all request uploaded files. 
     */
    public object $files;

    /**
     * @var array $json
     */
    public array $json;

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
    private function init(): void
    {
        /* method and route */
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->route = (object)[
            'method' => $_SERVER['REQUEST_METHOD'],
            'uri' => $_SERVER['REDIRECT_URL'],
        ];

        /* headers */
        $this->headers = (object)array_flip((array_map(function ($hKey) {
            return strtolower(str_replace('-', '_', $hKey));
        },  array_flip(getallheaders()))));

        /** json */
        $json = file_get_contents('php://input');
        $json = json_decode($json, true);

        $this->query = (object)$_GET;
        $this->body = (object)$_POST;
        $this->files = (object)$_FILES;
        $this->json = $json;
    }
}
