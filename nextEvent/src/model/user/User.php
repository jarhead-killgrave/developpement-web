<?php

namespace model\user;

/**
 * User is a class that represents an account of a user.
 *
 * @author 22013393
 * @version 1.0
 */
class User
{
    /**
     * The name of the user.
     *
     * @var string
     */
    private string $name;

    /**
     * The login of the user.
     *
     * @var string
     */
    private string $login;

    /**
     * The password of the user.
     *
     * @var string
     */
    private string $password;

    /**
     * The status of the user.
     *
     * @var string
     */
    private string $status;

    /**
     * The constructor of the user.
     *
     * @param string $name The name of the user.
     * @param string $login The login of the user.
     * @param string $password The password of the user.
     * @param string $status The status of the user.
     */
    public function __construct(string $name, string $login, string $password, string $status = "user")
    {
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;
        $this->status = $status;
    }

    /**
     * Get the name of the user.
     *
     * @return string The name of the user.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the user.
     *
     * @param string $name The name of the user.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the login of the user.
     *
     * @return string The login of the user.
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Set the login of the user.
     *
     * @param string $login The login of the user.
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * Get the password of the user.
     *
     * @return string The password of the user.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the password of the user.
     *
     * @param string $password The password of the user.
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Get the status of the user.
     *
     * @return string The status of the user.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the status of the user.
     *
     * @param string $status The status of the user.
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }


}
