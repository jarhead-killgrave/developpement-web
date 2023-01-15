<?php

namespace vue;

use model\user\User;
use model\user\UserBuilder;
use utils\FormBuilder;
use App\Router;

/**
 * The web view is a class that permits to display all page related to the user.
 *
 * @author 22013393
 * @version 1.0
 */
class UserView extends WebView
{
    /**
     * The constructor of the user view.
     *
     * @param Router $router The router.
     * @param ?array $feedback The feedback.
     */
    public function __construct(Router $router, ?array $feedback = null)
    {
        parent::__construct($router, $feedback);

    }

    /**
     * Make the user list page.
     *
     * @param array $users The users.
     */
    public function makeUserList(array $users): void
    {
        // The title of the page.
        $this->setTitle("User list");

        ob_start();
        include "templates/users/user-liste-page.php";
        $this->setContent(ob_get_clean());

        // Add the style of the page.
        $this->addStyle("user-liste-page");

    }

    /**
     * Make the registration form.
     *
     * @param UserBuilder $userBuilder The user builder.
     */
    public function makeUserCreationForm(UserBuilder $userBuilder): void
    {
        // The title of the page.
        $this->setTitle("User registration");

        $this->addStyle("form");

        $form = new FormBuilder($this->getRouter()->getRoute("user", ["action" => "store"]), "POST");

        // The name of the user.
        $form->addField(UserBuilder::$NAME_REF, "text", "Enter your name", "Name", $userBuilder->getData(UserBuilder::$NAME_REF));

        // The login of the user.
        $form->addField(UserBuilder::$LOGIN_REF, "text", "Enter your login", "Login", $userBuilder->getData(UserBuilder::$LOGIN_REF));

        // The password of the user.
        $form->addField(UserBuilder::$PASSWORD_REF, "password", "Enter your password", "Password", $userBuilder->getData(UserBuilder::$PASSWORD_REF));

        // The password confirmation of the user.
        $form->addField(UserBuilder::$PASSWORD_CONFIRM_REF, "password", "Confirm your password", "Password confirmation", $userBuilder->getData(UserBuilder::$PASSWORD_CONFIRM_REF));

        // Add the submit button.
        $form->addSubmit("submit", "Register");

        // Add errors.
        $form->addErrors($userBuilder->getErrors());

        // Add the form to the page.
        $this->setContent($form->build());


    }


    /**
     * Make the user login form.
     *
     * @param UserBuilder $userBuilder The user builder.
     */
    public function makeUserLoginForm(UserBuilder $userBuilder): void
    {
        $this->setTitle("Login");
        $this->addStyle("form");

        $form = new FormBuilder($this->getRouter()->getConnectRoute(), "POST");

        $form->addField(UserBuilder::$LOGIN_REF, "text", "Login", "Enter your login", $userBuilder->getData(UserBuilder::$LOGIN_REF));
        $form->addField(UserBuilder::$PASSWORD_REF, "password", "Password", "Enter your password", "", "", "", "");

        $form->addSubmit("Login");

        $form->addErrors($userBuilder->getErrors());
        $content = $form->build();

        // We add the link to the registration page.
        $content .= "<p class='text-info'>Don't have an account ? <a class='link' href='" . $this->getRouter()->getRegisterPage() . "'>Register</a></p>";

        $this->setContent($content);
    }

    /**
     * Display successful connection message.
     */
    public function displaySuccessfulConnection(): void
    {
        $feedback = [
            "message" => "You are now connected !",
            "status" => "success"
        ];

        $this->getRouter()->postRedirectGet($this->getRouter()->getHomePage(), $feedback);
    }

    public function displaySuccessfulRegistration(): void
    {
        $this->getRouter()->postRedirectGet($this->getRouter()->getHomePage(), ["message" => "You are now registered !", "status" => "success"]);
    }

    /**
     * Make logout page.
     */
    public function makeUserLogoutForm(): void
    {
        // Set the title of the page
        $this->setTitle("Logout");
        $form = new FormBuilder($this->getRouter()->getDisconnectPage(), "POST");
        $form->addSubmit("Logout");

        $content = "<p>Are you sure you want to log out ?</p>";
        $content .= $form->build();

        $this->setContent($content);
    }

    /**
     * Make the user profile page.
     *
     * @param User $user The user.
     * @param int $nbPosts The number of posts.
     */
    public function makeUserProfilePage(User $user, int $nbPosts): void
    {
        $this->setTitle("User profil");
        $this->addStyle("user-profile-page");
        ob_start();
        include "templates/users/account.php";
        $this->setContent(ob_get_clean());

    }



}
