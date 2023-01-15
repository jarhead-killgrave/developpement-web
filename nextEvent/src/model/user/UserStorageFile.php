<?php

namespace model\user;

use Exception;
use lib\ObjectFileDB;

/**
 * User storage is a class that permits to manage the storage of the users.
 *
 * @author 22013393
 * @version 1.0
 */
class UserStorageFile implements UserStorage
{
    /**
     * The object storage.
     *
     * @var ObjectFileDB
     */
    private ObjectFileDB $storage;

    /**
     * The constructor of the user storage file.
     *
     * @param string $path The path of the file.
     */
    public function __construct(string $path)
    {
        $exists = file_exists($path);
        $this->storage = new ObjectFileDB($path);
        if (!$exists) {
            $this->reinitialize();
        }
    }

    /**
     * Initialize the storage.
     *
     * @return bool True if the initialization is successful, false otherwise.
     */
    public function reinitialize(): bool
    {
        $users = [
            new User("admin", "admin", password_hash("@6fqKQO^58nI!D!", PASSWORD_DEFAULT), "admin"),
        ];

        // for each user
        foreach ($users as $user) {
            // create the user
            $this->create($user);
        }

        return true;
    }

    public function create($data): string
    {
        return $this->storage->insert($data);
    }

    public function read(string $id): ?UserDb
    {
        // If the user is not found, we return null.
        if (!$this->storage->exists($id)) {
            return null;
        }
        return UserDb::associate($id, $this->storage->fetch($id));
    }

    public function update(string $id, $data): bool
    {
        // try to update the user
        try {
            $this->storage->update($id, $data);
            return true;
        } catch (Exception $e) {
            // if the user is not found
            return false;
        }
    }

    public function delete(string $id): bool
    {
        // try to delete the user
        try {
            $this->storage->delete($id);
            return true;
        } catch (Exception $e) {
            // if the user is not found
            return false;
        }
    }

    public function deleteAll(): bool
    {
        try {
            $this->storage->deleteAll();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function readByLogin(string $login): ?UserDb
    {
        // For each user
        foreach ($this->readAll() as $id => $user) {
            // If the login is the same
            if ($user->getLogin() === $login) {
                return UserDb::associate($id, $user);
            }
        }
        // if the user is not found
        return null;
    }

    public function readAll(): array
    {
        return $this->storage->fetchAll();
    }

    public function count(): int
    {
        return count($this->storage->fetchAll());
    }

    /**
     * Count the number events of a user.
     *
     * @param string $id The id of the user.
     */
    public function countEvents(string $id): int
    {
        // We can't access to the event storage from here, so we return 0.
        return 0;
    }
}
