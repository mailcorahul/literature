<?php

namespace AppBundle\Exceptions;

use \Exception;

/**
 *
 */
class BadRequestException extends Exception
{

    function __construct(
        string $message,
        array $extra = array(),
        int $code = 0,
        Exception $previous = null)
    {

        parent::__construct($message, $code, $previous);

        $this->extra = $extra;
    }

    public function __toString() {

        return $this->message;
    }

    public function getExtra() {

        return $this->extra;
    }
}