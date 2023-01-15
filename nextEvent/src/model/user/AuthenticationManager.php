<?php

namespace model\user;

use utils\Session;

/**
 * AuthenticationManager is a class that permits to manage the authentication of the users.
 *
 * @author 22013393
 * @version 1.0
 */
class AuthenticationManager
{

    /**
     * The user storage.
     *
     * @var UserStorage
     */
    private UserStorage $userStorage;

    /**
     * The constructor of the authentication manager.
     *
     * @param UserStorage $userStorage The user storage.
     */
    public function __construct(UserStorage $userStorage)
    {
        $this->userStorage = $userStorage;
    }

    /**
     * Disconnect the user.
     *
     * @return void
     */
    public static function disconnect(): void
    {
        $_SESSION['user'] = null;
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool True if the user is an admin, false otherwise.
     */
    public static function isAdmin(): bool
    {
        return self::isConnected() && $_SESSION['user']->isAdmin();
    }

    /**
     * Check if the user is connected.
     *
     * @return bool True if the user is connected, false otherwise.
     */
    public static function isConnected(): bool
    {
        return array_key_exists('user', $_SESSION) && $_SESSION['user'] !== null;
    }

    /**
     * Get the user.
     *
     * @return User|null The user if the user is connected, null otherwise.
     */
    public static function getUser(): ?User
    {
        return self::isConnected() ? $_SESSION['user'] : null;
    }

    /**
     * The method that permits to register a user.
     *
     * @param UserBuilder $userBuilder The user builder.
     * @return bool True if the user is registered, false otherwise.
     * @throws Exception If we cannot builder for create the user.
     */
    public function register(UserBuilder $userBuilder): bool
    {
        $user = $userBuilder->buildUser();
        // We check if the user already exists.
        if ($this->getUserByLogin($user->getLogin()) === null) {
            $this->userStorage->create($user);
            return true;
        }
        return false;
    }

    public function getUserByLogin($login): ?UserDb
    {
        return $this->userStorage->readByLogin($login);
    }

    /**
     * The method that permits to login a user.
     *
     * @param UserBuilder $userBuilder The user builder.
     * @return bool True if the user is logged in, false otherwise.
     */
    public function login(UserBuilder $userBuilder): bool
    {
        $userFromStorage = $this->getUserByLogin($userBuilder->getData(UserBuilder::$LOGIN_REF));
        if ($userFromStorage !== null &&
            password_verify($userBuilder->getData(UserBuilder::$PASSWORD_REF), $userFromStorage->getPassword())) {
            $_SESSION['user'] = $userFromStorage;
            return true;
        }
        return false;
    }


}
