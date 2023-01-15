<?php

namespace controller;

use vue\ErrorView;

/**
 * ErrorController is a class that permits to manage errors.
 *
 * The user can manage errors.
 *
 * @author 22013393
 * @version 1.0
 */
class ErrorController extends Controller
{
    /**
     * The message of the error.
     * @var string
     */
    private string $message;


    /**
     * Build an error controller.
     *
     * @param ErrorView $errorView The error view.
     * @param string $message The message of the error.
     */
    public function __construct(ErrorView $errorView, string $message = "")
    {
        parent::__construct($errorView);
        $this->message = $message;
    }

    /**
     * Not found page.
     *
     * @return void
     */
    public function notFound(): void
    {
        $this->getWebView()->makeNotFoundPage();
    }

    /**
     * Forbidden page.
     *
     * @return void
     */
    public function forbidden(): void
    {
        $this->getWebView()->makeForbiddenPage();
    }

    /**
     * Internal server error page.
     *
     * @return void
     */
    public function internalServerError(): void
    {
        $this->getWebView()->makeInternalServerErrorPage($this->message);
    }

    /**
     * Bad request page.
     *
     * @return void
     */
    public function badRequest(): void
    {
        $this->getWebView()->makeBadRequestPage();
    }

    /**
     * Unauthorized page.
     *
     * @return void
     */
    public function unauthorized(): void
    {
        $this->getWebView()->makeUnauthorizedPage();
    }


}

