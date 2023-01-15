<?php

namespace exceptions;

use Exception;
use Throwable;

/**
 * BadRequestException is a class that permits to manage exceptions that are thrown when a bad request is made.
 *
 * @author 22013393
 * @version 1.0
 */
class BadRequestException extends Exception
{
    /**
     * The constructor of the exception.
     *
     * @param string $request The request that is bad.
     * @param int $code The code of the exception.
     * @param Throwable $previous The previous exception.
     */
    public function __construct(string $request = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct("The request $request is bad.", $code, $previous);
    }
}
