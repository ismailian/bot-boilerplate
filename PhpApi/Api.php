<?php

namespace PhpApi;

use PhpApi\Core\Functions\Router;
use PhpApi\Core\Helpers\Cors;

/**
 * The Api App class
 */
class Api
{

    use Router;
    use Cors;

    /**
     * default constructor
     * @param array|null $options
     */
    public function __construct(array $options = null)
    {
        if (!is_null($options)) {

            /** assign prefix */
            if (isset($options['prefix']))
                $this->prefix = $options['prefix'];

            /** assign cors */
            if (isset($options['cors'])) {
                $this->cors = $options['cors'];
            }
        }

        /** implement Cors for this api app */
        $this->cors($this->cors);
    }
}
