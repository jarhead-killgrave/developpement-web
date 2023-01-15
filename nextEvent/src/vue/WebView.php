<?php

namespace vue;

use App\Router;

/**
 * The web view is a class that permits to display the web page.
 *
 * @author 22013393
 * @version 1.0
 */
class WebView implements View
{
    /**
     * The title of the page.
     *
     * @var string
     */
    private string $title;

    /**
     * The content of the page.
     *
     * @var string
     */
    private string $content;

    /**
     * The style of the page.
     */
    private array $styles;

    /**
     * The feedback of the page.
     *
     * @var ?array
     */
    private ?array $feedback;

    /**
     * The router.
     * @var Router
     */
    private Router $router;


    /**
     * The constructor of the web view.
     *
     * @param Router $router The router.
     * @param ?array $feedback The feedback.
     */
    public function __construct(Router $router, array $feedback = null)
    {
        $this->title = "";
        $this->content = "";
        $this->styles = ["global", "template", "menu"];
        $this->router = $router;
        $this->feedback = $feedback;
    }


    /**
     * Get the title of the page.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the title of the page.
     *
     * @param string $title The title of the page.
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Get the content of the page.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the content of the page.
     *
     * @param string $content The content of the page.
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Get the style of the page.
     *
     * @return array The style of the page.
     */
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * Set style of the page.
     *
     * @param array $styles The style of the page.
     */
    public function setStyles(array $styles): void
    {
        $this->styles = $styles;
    }

    /**
     * Display the page.
     *
     * @return void
     */
    public function render(): void
    {

        include 'templates/template.php';
    }

    /**
     * Get the feedback of the page.
     *
     * @return ?array The feedback of the page.
     */
    public function getFeedback(): ?array
    {
        return $this->feedback;
    }

    /**
     * Set the feedback of the page.
     *
     * @param ?array $feedback The feedback of the page.
     */
    public function setFeedback(?array $feedback): void
    {
        $this->feedback = $feedback;
    }

    /**
     * Make the home page
     *
     * @return void
     */
    public function makeHomePage(): void
    {
        $this->setTitle("Home");
        $this->addStyle("home");
        $this->getMenu()["Home"]["active"] = true;
        ob_start();
        include 'templates/home.php';
        $this->setContent(ob_get_clean());

    }

    /**
     * add a style to the page.
     *
     * @param string $style The style to add.
     */
    public function addStyle(string $style): void
    {
        $this->styles[] = $style;
    }

    /**
     * Get the menu of the page.
     *
     * @return array The menu of the page.
     */
    public function getMenu(): array
    {

        return [
            "Home" => array("route" => $this->getRouter()->getHomePage(), "active" => false),
            "Events" => array("route" => $this->getRouter()->getEventListPage(), "active" => false),
            "About" => array("route" => $this->getRouter()->getAboutPage(), "active" => false),
        ];

    }

    /**
     * Get the router.
     * @return Router The router.
     */
    public function getRouter(): Router
    {
        return $this->router;
    }


}
