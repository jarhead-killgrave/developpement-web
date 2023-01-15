<?php

namespace controller;

use model\user\AuthenticationManager;
use model\user\UserBuilder;
use model\user\UserStorage;
use utils\Input;
use vue\UserView;

/**
 * UserController is a class that permits to manage the users.
 *
 * @author 22013393
 * @version 1.0
 */
class UserController extends Controller
{

    /**
     * The user storage.
     *
     * @var UserStorage
     */
    private UserStorage $userStorage;

    /**
     * The userBuilder for register.
     *
     * @var ?UserBuilder
     */
    private ?UserBuilder $userBuilderRegister;

    /**
     * The userBuilder for login.
     *
     * @var ?UserBuilder
     */
    private ?UserBuilder $userBuilderLogin;

    /**
     * The parameter of the controller.
     * @var ?string
     */
    private ?string $paramater;

    /**
     * The constructor of the user controller.
     *
     * @param UserView $userView The user view.
     * @param UserStorage $userStorage The user storage.
     * @param ?string $param The parameter of the controller.
     */
    public function __construct(UserView $userView, UserStorage $userStorage, ?string $param)
    {
        parent::__construct($userView);
        $this->userStorage = $userStorage;
        $this->paramater = $param;
        $this->userBuilderRegister = $_SESSION['userBuilderRegister'] ?? null;
        $this->userBuilderLogin = $_SESSION['userBuilderLogin'] ?? null;
    }

    public function __destruct()
    {
        $_SESSION['userBuilderRegister'] = $this->userBuilderRegister;
        $_SESSION['userBuilderLogin'] = $this->userBuilderLogin;
    }

    /**
     * Display all the users.
     *
     */
    public function list(): void
    {
        $this->getWebView()->makeUserList($this->userStorage->readAll());
    }

    /**
     * Show the form for the user to logout.
     *
     * @return void
     */
    public function logout(): void
    {
        $this->getWebView()->makeUserLogoutForm();
    }

    /**
     * Connect the user.
     *
     */
    public function connect(): void
    {
        // get needed data
        $data = array_intersect_key($_POST, array_flip(UserBuilder::getNeededKeys()));
        // create a new userBuilder
        $data = array_map( static fn($value) => Input::sanitizeFullSpecialChars($value), $data);
        $this->userBuilderLogin = new UserBuilder($data);

        // create a new authenticationManager
        $authenticationManager = new AuthenticationManager($this->userStorage);
        // check if the login is failed
        if (!$authenticationManager->login($this->userBuilderLogin)) {
            $this->userBuilderLogin->setError(UserBuilder::$LOGIN_REF, "The login doesn't exist or the password is incorrect.");
            $this->userBuilderLogin->setError(UserBuilder::$PASSWORD_REF, "The login doesn't exist or the password is incorrect.");
            $this->getWebView()->makeUserLoginForm($this->userBuilderLogin);
        } else {
            $this->userBuilderLogin = null;
            $this->getWebView()->displaySuccessfulConnection();
        }
    }

    /**
     * Show the form for the user to login.
     *
     * @return void
     */
    public function login(): void
    {
        // If the userBuilder is not set, create a new one.
        if ($this->userBuilderLogin === null) {
            $this->userBuilderLogin = new UserBuilder([]);
        }
        $this->getWebView()->makeUserLoginForm($this->userBuilderLogin);
    }

    /**
     * Disconnect the user.
     *
     * @return void
     */
    public function disconnect(): void
    {
        // disconnect the user
        AuthenticationManager::disconnect();
        // redirect to the home page
        $this->getWebView()->makeHomePage();
    }

    /**
     * Create the user.
     *
     * @param array $data The data of the user.
     */
    public function store(): void
    {
        // Retrieve only needed data.
        $data = array_intersect_key($_POST, array_flip(UserBuilder::getNeededKeys()));

        $data = array_map( static fn($value) => Input::sanitizeFullSpecialChars($value), $data);

        $this->userBuilderRegister = new UserBuilder($data);

        // Check if the data are valid.
        if ($this->userBuilderRegister->isValid()) {
            // Create the user.
            $authenticationManager = new AuthenticationManager($this->userStorage);
            if ($authenticationManager->register($this->userBuilderRegister)) {
                $this->userBuilderRegister = null;
                $this->getWebView()->displaySuccessfulRegistration();
            } else {
                $this->userBuilderRegister->setError(UserBuilder::$LOGIN_REF, "The login already exists.");
                $this->getWebView()->makeUserCreationForm($this->userBuilderRegister);
            }
        } else {
            $this->getWebView()->makeUserCreationForm($this->userBuilderRegister);
        }
    }

    /**
     * Show the creation form of the user.
     *
     */
    public function register(): void
    {
        // If the userBuilder is not set, create a new one.
        if ($this->userBuilderRegister === null) {
            $this->userBuilderRegister = new UserBuilder([]);
        }
        $this->getWebView()->makeUserCreationForm($this->userBuilderRegister);
    }

    /**
     * Show account page.
     *
     * @return void
     */
    public function account(): void
    {
        // get the user
        $user = $this->userStorage->read($_SESSION["user"]->getId() ?? "");
        // if the user exists
        if ($user !== null) {
            // We get the number of the user's posts
            $nbPosts = $this->userStorage->countEvents($user->getId());
            // show the account page
            $this->getWebView()->makeUSerProfilePage($user, $nbPosts);
        } else {
            // if the user does not exist, redirect to the home page
            $this->getWebView()->makeHomePage();
        }
    }


}
