<?php

namespace PhpApi\Core\Functions;

use PhpApi\Core\Helpers\AsyncResponder;
use PhpApi\Core\Helpers\Responder;

/**
 * The response class.
 * Once initialized it automatically prepares for a response to be delivered.
 */
class Response
{

    use Responder;
    use AsyncResponder;

    /**
     * default constructor
     */
    public function __construct() { }

    /**
     * send response to the client.
     *
     * @param mixed $data the content data to be sent.
     */
    public function send($data)
    {
        $this->prepare()->terminate($data);
    }

    /**
     * respond with json data.
     * 
     * @param mixed $data the data to be sent back to the client.
     */
    public function json($data)
    {
        $this->headers['Content-Type'] = 'application/json';
        $this->prepare()->terminate(json_encode($data, JSON_UNESCAPED_SLASHES));
    }

    /**
     * respond with text data
     * @param mixed $data the data to be sent back to the client.
     */
    public function text($data)
    {
        $this->headers['Content-Type'] = 'text/plain';
        $this->prepare()->terminate($data);
    }

    /**
     * respond with html data
     * @param mixed $data the data to be sent back to the client.
     */
    public function html($data)
    {
        $this->headers['Content-Type'] = 'text/html';
        $this->prepare()->terminate($data);
    }
}
