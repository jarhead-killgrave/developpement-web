<?php

namespace model\user;

/**
 * Class that permits to represent a user as it is stored in the database.
 */
class UserDb extends User
{
    /**
     * The user's id.
     */
    private string $id;

    /**
     * Constructor of the user.
     *
     * @param string $id The user's id.
     * @param string $name The user's name.
     * @param string $login The user's login.
     * @param string $password The user's password.
     * @param string $status The user's status.
     */
    public function __construct(string $id, string $name, string $login, string $password, string $status = "user")
    {
        parent::__construct($name, $login, $password, $status);
        $this->id = $id;
    }

    /**
     * Associate the user to an id.
     *
     * @param string $id The user's id.
     * @param User $user The user.
     */
    public static function associate(string $id, User $user): UserDb
    {
        return new UserDb($id, $user->getName(), $user->getLogin(), $user->getPassword(), $user->getStatus());
    }

    /**
     * Get the user's id.
     *
     * @return string The user's id.
     */
    public function getId(): string
    {
        return $this->id;
    }


}

