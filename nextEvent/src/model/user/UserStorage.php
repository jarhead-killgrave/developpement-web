<?php

namespace model\user;

use utils\Storage;

/**
 * User storage is a class that permits to manage the storage of the users.
 *
 * @author 22013393
 * @version 1.0
 */
interface UserStorage extends Storage
{
    /**
     * Read the user by the login.
     *
     * @param string $login The login of the user.
     *
     * @return UserDb|null The user if the user is found, null otherwise.
     */
    public function readByLogin(string $login): ?UserDb;


}
