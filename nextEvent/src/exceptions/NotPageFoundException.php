<?php

namespace exceptions;

use Exception;
use Throwable;

/**
 * NotPageFoundException is a class that permits to manage exceptions that are thrown when a page is not found.
 *
 * @author 22013393
 * @version 1.0
 */
class NotPageFoundException extends Exception
{
    /**
     * The constructor of the exception.
     *
     * @param string $page The page that is not found.
     * @param int $code The code of the exception.
     * @param Throwable $previous The previous exception.
     */
    public function __construct(string $page = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct("The page $page was not found.", $code, $previous);
    }
}
