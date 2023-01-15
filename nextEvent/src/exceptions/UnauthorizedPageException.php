<?php

namespace exceptions;

use Exception;
use Throwable;

/**
 * The UnauthorizedPageException is a class that permits to manage the unauthorized page exception.
 */
class UnauthorizedPageException extends Exception
{
    /**
     * The constructor of the unauthorized page exception.
     *
     * @param int $code The code of the exception.
     * @param ?Throwable $previous The previous exception.
     */
    public function __construct(int $code = 0, Throwable $previous = null)
    {
        parent::__construct("You are not allowed to access this page.", $code, $previous);
    }

}
