<?php

namespace App;

use controller\Controller;
use controller\ErrorController;
use controller\EventController;
use controller\UserController;
use Exception;
use exceptions\BadRequestException;
use exceptions\NotPageFoundException;
use model\user\UserStorage;
use utils\Input;
use utils\Storage;
use vue\ErrorView;
use vue\EventView;
use vue\UserView;
use vue\WebView;

/**
 * Router is a class that permits to route requests to the right controller.
 *
 * The user can route requests to the right controller.
 */
class Router
{
    /**
     * The action that the visitor are allowed to do.
     *
     * @var array
     */
    private array $visitorActions = [
        "event" => [
            "liste",
            "home",
            "about",
            "search"
        ],
        "user" => [
            "login",
            "register",
            "store",
            "connect"
        ]
    ];


    /**
     * The debug mode.
     */
    private bool $debug;
    /**
     * The storage.
     *
     * @var Storage
     */
    private Storage $storage;

    /**
     * The user storage.
     *
     * @var UserStorage
     */
    private UserStorage $userStorage;

    /**
     * The current view.
     *
     * @var WebView
     */
    private WebView $view;

    /**
     * The current controller.
     *
     * @var Controller
     */
    private Controller $controller;


    /**
     * The constructor.
     *
     * @param Storage $storage The storage.
     * @param UserStorage $userStorage The user storage.
     * @param bool $debug The debug mode.
     */
    public function __construct(Storage $storage, UserStorage $userStorage, bool $debug = false)
    {
        $this->storage = $storage;
        $this->userStorage = $userStorage;
        $this->debug = $debug;
        $this->view = new ErrorView($this, $this->debug);
        $this->controller = new ErrorController($this->view, $this->debug);
    }

    /**
     * Get the link to the upload directory.
     *
     * @return string The link to the upload directory.
     */
    public static function getUploadDirectory(): string
    {
        // We get the directory of the excuting script
        $directory = dirname($_SERVER['SCRIPT_FILENAME']);
        // We add the upload directory to the directory of the project
        return $directory . '/upload/';
    }

    /**
     * Get the file from the upload directory.
     *
     * @param string $file The file.
     * @return string The file from the upload directory.
     */
    public static function getUploadFile(string $file): string
    {
        $scriptName = str_replace('events.php', '', $_SERVER['SCRIPT_NAME']);
        return $scriptName . 'upload/' . $file;
    }

    /**
     * Delete the file from the upload directory.
     * @param string $file The file.
     */
    public static function deleteUploadFile(string $file): void
    {
        // directory of the upload according to the server
        $directory = dirname($_SERVER['SCRIPT_FILENAME']) . '/upload/';

        // We check if the file exists
        if (file_exists($directory . $file)) {
            // We delete the file
            unlink($directory . $file);
        }
    }

    /**
     * Route the request
     *
     * @return void
     */
    public function route(): void
    {
        // Run the session if it is not already started
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cache_limiter', 'public');
            session_name('NextEvent');
            session_cache_limiter(false);
            // Set the session cookie to expire in 1 hour
            session_set_cookie_params(3600);
            session_start();
        }
        // check that path info is not malicious
        $request = "";
        if (array_key_exists("PATH_INFO", $_SERVER)) {
            $request = Input::sanitizeFullSpecialChars($_SERVER["PATH_INFO"]);
        } else {
            $this->redirect("event/home");
        }

        // The page to display.
        $page = strtok($request, '/');
        // The action to do.
        $action = strtok('/');
        // The parameter of the action to do or null.
        $parameter = strtok('/');


        // If the page is false, it means that the request is empty.
        if ($page === false) {
            $page = 'event';
        }

        // If the action is false, it means that the request is empty.
        if ($action === false) {
            $action = 'home';
        }

        // If the parameter is false, it means that the request is empty.
        if ($parameter === false) {
            $parameter = null;
        }

        // Get the user.
        $user = $_SESSION['user'] ?? null;

        // Get the feedback.
        $feedback = $_SESSION['feedback'] ?? null;
        // Delete the feedback.
        $_SESSION['feedback'] = null;

        try {

            switch ($page) {
                case 'event':
                    $this->view = new EventView($this, $feedback);
                    $this->controller = new EventController($this->view, $this->storage, $this->userStorage, $parameter);
                    break;
                case 'user':
                    $this->view = new UserView($this);
                    $this->controller = new UserController($this->view, $this->userStorage, $parameter);
                    break;
                default:
                    throw new NotPageFoundException();
            }

            if ($user === null && !in_array($action, $this->visitorActions[$page], true)) {
                $this->redirect("user/login");
            }

            // If the action exists, we call it.
            if (method_exists($this->controller, $action)) {
                $this->controller->$action();
            } else {
                throw new NotPageFoundException();
            }

        } catch (Exception $e) {
            $this->error($e);
        }

        // Display the view.
        $this->view->render();
    }

    /**
     * Redirect to the given page.
     *
     * @param string $page The page.
     */
    public function redirect(string $page): void
    {
        // url is the script name followed by the page
        $url = $_SERVER['SCRIPT_NAME'] . '/' . $page;

        // Redirect to the url
        header('Location: ' . $url, 303);
        exit();


    }

    /**
     * Manage the error.
     *
     * @param Exception $e The exception.
     */
    private function error(Exception $e): void
    {
        // If the view is not an error view.
        if (!($this->view instanceof ErrorView)) {
            // We change the view.
            $this->view = new ErrorView($this, $this->debug);
            // We change the controller.
            $this->controller = new ErrorController($this->view, $this->debug);
        }
        // Set the action to call.
        $action = 'internalServerError';
        // If the exception is a BadRequestException.
        if ($e instanceof BadRequestException) {
            // Set the action to call.
            $action = 'badRequest';
        } else if ($e instanceof NotPageFoundException) {
            // Set the action to call.
            $action = 'notFound';
        }
        // Call the action.
        $this->controller->$action();
    }

    /**
     * Post redirect get.
     *
     * @param string $url The url.
     * @param mixed $feedback The feedback.
     * @return void The redirection.
     */
    public function postRedirectGet(string $url, $feedback): void
    {
//        Session::set('nextEvent-feedback', $feedback);
        $_SESSION['feedback'] = $feedback;
        header('Location: ' . $url, true, 303);
        exit();
    }

    /**
     * Redirect to the given page with the given parameters.
     *
     * @param string $page The page.
     * @param bool $feedback If the feedback must be displayed.
     * @param array $parameters The parameters.
     */
    public function redirectWithParameters(string $page, bool $feedback, array $parameters): void
    {
        // The url is the script name followed by the page
        $url = $_SERVER['SCRIPT_NAME'] . '/' . $page;
        $parameters = array_map('urlencode', $parameters);
        // The parameters are added to the url
        $url .= '/' . implode('/', $parameters);
        // The user is redirected to the url
        $this->redirect($url, $feedback);
    }

    /**
     * Get current route.
     *
     * @return string
     */
    public function getCurrentRoute(): string
    {
        $request = $_SERVER['PATH_INFO'] ?? '/';
        // Remove the script name from the request
        $request = str_replace($_SERVER['SCRIPT_NAME'], '', $request);
        return $request;
    }

    /**
     * Get route.
     *
     * @param string $page The page.
     * @param array $parameters The parameters.
     */
    public function getRoute(string $page, array $parameters = []): string
    {
        $url = $_SERVER['SCRIPT_NAME'] . '/' . $page;
        $parameters = array_map('urlencode', $parameters);
        // The parameters are added to the url
        $url .= '/' . implode('/', $parameters);
        return $url;
    }

    /**
     * Get the connect route.
     *
     * @return string The connect route.
     */
    public function getConnectRoute(): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/user/connect';
    }

    /**
     * Get the home page.
     *
     * @return string The home page.
     */
    public function getHomePage(): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/event/home';
    }

    /**
     * Get the login page.
     *
     * @return string The login page.
     */
    public function getLoginPage(): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/user/login';
    }

    /**
     * Get the register page.
     *
     * @return string The register page.
     */
    public function getRegisterPage(): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/user/register';
    }

    /**
     * Get the logout page.
     *
     * @return string The logout page.
     */
    public function getLogoutPage(): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/user/logout';
    }

    /**
     * Get the disconnect page.
     *
     * @return string The disconnect page.
     */
    public function getDisconnectPage(): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/user/disconnect';
    }

    /**
     * Get account page.
     *
     * @return string The account page.
     */
    public function getAccountPage(): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/user/account';
    }

    /**
     * Check if the user is connected.
     *
     * @return bool If the user is connected.
     */
    public function isLogged(): bool
    {
        return ($_SESSION['user'] ?? null) !== null;
    }

    /**
     * Get the index event page.
     *
     * @param int $page The page.
     * @return string The index event page.
     */
    public function getEventListPage(int $page = 1): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/event/liste/' . $page;
    }

    /**
     * Get page of the event with the given id.
     *
     * @param string $id The id of the event.
     * @return string The page of the event with the given id.
     */
    public function getEventPage(string $id): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/event/show/' . $id;
    }

    /**
     * Get the page for the creation of an event.
     *
     * @return string The page for the creation of an event.
     */
    public function getCreateEventPage(): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/event/create';
    }

    /**
     * Get search result page.
     * @return string The search result page.
     */
    public function getSearchResultPage(): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/event/search';
    }

    /**
     * Update the event with the given id.
     *
     * @param string $id The id of the event.
     */
    public function getUpdateEventPage(string $id): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/event/edit/' . $id;
    }

    /**
     * Get the page for asking the confirmation of the deletion of the event with the given id.
     *
     * @param string $id The id of the event.
     * @return string The page for asking the confirmation of the deletion of the event with the given id.
     */
    public function getDeleteEventPage(string $id): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/event/delete/' . $id;
    }

    /**
     * Get the About page.
     *
     * @return string The About page.
     */
    public function getAboutPage(): string
    {
        return $_SERVER['SCRIPT_NAME'] . '/event/about';
    }

    /**
     * Get the css file.
     *
     * @param string $file The css file.
     * @return string The css file.
     */
    public function getCssFile(string $file): string
    {
        // We remove the 'events.php' from the script name
        $scriptName = str_replace('events.php', '', $_SERVER['SCRIPT_NAME']);
        // We add 'assets/css/$file.css' to the script name
        return $scriptName . 'assets/css/' . $file . '.css';
    }

    /**
     * Get the image file.
     *
     * @param string $file The image file.
     * @return string The image file.
     */
    public function getImageFile(string $file): string
    {
        // We remove the 'events.php' from the script name
        $scriptName = str_replace('events.php', '', $_SERVER['SCRIPT_NAME']);
        // We add 'assets/img/$file' to the script name
        return $scriptName . 'assets/img/' . $file;
    }

    /**
     * Get the javascript file.
     *
     * @param string $file The javascript file.
     */
    public function getJavascriptFile(string $file): string
    {
        // We remove the 'events.php' from the script name
        $scriptName = str_replace('events.php', '', $_SERVER['SCRIPT_NAME']);
        // We add 'assets/js/$file.js' to the script name
        return $scriptName . 'assets/js/' . $file . '.js';
    }

    /**
     * Check if a url is active.
     *
     * @param string $url The url.
     * @return bool If the url is active.
     */
    public function isActive(string $url): bool
    {
        return $url === $_SERVER['REQUEST_URI'];
    }


}
