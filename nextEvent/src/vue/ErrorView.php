<?php

namespace vue;


use App\Router;

/**
 * The ErrorView is a class that permits to display the error page.
 *
 * @author 22013393
 * @version 1.0
 */
class ErrorView extends WebView
{
    /**
     * Boolean that indicates if we are in debug mode.
     *
     * @var boolean
     */
    private bool $debug;

    /**
     * The constructor of the error view.
     *
     * @param Router $router The router.
     * @param boolean $debug Boolean that indicates if we are in debug mode.
     */
    public function __construct(Router $router, bool $debug = false)
    {
        parent::__construct($router);
        $this->debug = $debug;
    }


    public function makePage(array $data): void
    {
        $this->setTitle("Error");
        $this->setContent("<h1>Error</h1>");

    }

    /**
     * Make not found page.
     *
     * @return void
     */
    public function makeNotFoundPage(): void
    {
        $this->setTitle("Not found");
        $content = "<p>The page you are looking for does not exist.</p>";
        $this->setContent($content);
    }

    /**
     * Make forbidden page.
     *
     * @return void
     */
    public function makeForbiddenPage(): void
    {
        $this->setTitle("Forbidden");
        $content = "<p>You are not allowed to access this page.</p>";
        $this->setContent($content);
    }

    /**
     * Make internal server error page.
     * @param string $message The message of the error.
     * @return void
     */
    public function makeInternalServerErrorPage(string $message): void
    {
        $this->setTitle("Internal server error");
        $content = "<p>An internal server error has occurred.</p>";
        if ($this->debug) {
            $content .= "<p>Description: $message</p>";
        }
        $this->setContent($content);
    }

    /**
     * Make bad request page.
     *
     * @return void
     */
    public function makeBadRequestPage(): void
    {
        $this->setTitle("Bad request");
        $content = "<p>The request is invalid.</p>";
        $this->setContent($content);
    }

    /**
     * Make unauthorized page.
     *
     * @return void
     */
    public function makeUnauthorizedPage(): void
    {
        $this->setTitle("Unauthorized");
        $content = "<p>You are not authorized to access this page.</p>";
        $this->setContent($content);
    }

    /**
     * Make service unavailable page.
     *
     * @return void
     */
    public function makeServiceUnavailablePage(): void
    {
        $this->setTitle("Service unavailable");
        $content = "<p>The service is unavailable.</p>";
        $this->setContent($content);
    }

    /**
     * Make gateway timeout page.
     *
     * @return void
     */
    public function makeGatewayTimeoutPage(): void
    {
        $this->setTitle("Gateway timeout");
        $content = "<p>The gateway has timed out.</p>";
        $this->setContent($content);
    }

    /**
     * Display the error page.
     *
     * @return void
     */
    public function render(): void
    {
        // If we are in debug mode, we display all global variables.
        if ($this->debug) {
            // If the session is not started, we start it.
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $this->setContent($this->getContent() . "<h2>Global variables</h2>");
            $this->setContent($this->getContent() . "<h3>GET</h3>");
            $this->setContent($this->getContent() . "<pre>" . print_r($_GET, true) . "</pre>");
            $this->setContent($this->getContent() . "<h3>POST</h3>");
            $this->setContent($this->getContent() . "<pre>" . print_r($_POST, true) . "</pre>");
            $this->setContent($this->getContent() . "<h3>SESSION</h3>");
            $this->setContent($this->getContent() . "<pre>" . print_r($_SESSION, true) . "</pre>");
            $this->setContent($this->getContent() . "<h3>COOKIE</h3>");
            $this->setContent($this->getContent() . "<pre>" . print_r($_COOKIE, true) . "</pre>");
            $this->setContent($this->getContent() . "<h3>SERVER</h3>");
            $this->setContent($this->getContent() . "<pre>" . print_r($_SERVER, true) . "</pre>");
        }
        parent::render();
    }
}
