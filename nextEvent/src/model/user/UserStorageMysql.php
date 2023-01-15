<?php

namespace model\user;

use PDO;

/**
 * Class that permits to manage the user.
 */
class UserStorageMysql implements UserStorage
{

    /**
     * The database.
     *
     * @var PDO
     */
    private PDO $database;

    /**
     * Constructor of the user storage.
     *
     * @param PDO $database The database.
     */
    public function __construct(PDO $database)
    {
        $this->database = $database;
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Renitialize the database if it does not exist
        if (!$this->database->query("SHOW TABLES LIKE 'users'")->fetch()) {
            $this->reinitialize();
        }
    }

    /**
     * Reinitalize the database.
     *
     * @return bool True if the database has been reinitalized, false otherwise.
     */
    public function reinitialize(): bool
    {
        // Drop the table
        $request = "DROP TABLE IF EXISTS users";

        // Execute the request
        $this->database->query($request);

        // Create the table
        $request = "CREATE TABLE users (
            id VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            login VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            status VARCHAR(255) NOT NULL,
            PRIMARY KEY (id)
        )";

        // Execute the request
        $this->database->query($request);


        // Insert the default users
        $users = [
            new User("admin", "admin", password_hash("@6fqKQO^58nI!D!", PASSWORD_DEFAULT), "admin")
        ];

        foreach ($users as $user) {
            $this->create($user);
        }


        return true;
    }

    /**
     * Create a user.
     *
     * @param User $data The user.
     * @return string The id of the user.
     */
    public function create($data): string
    {

        // Unique id
        $id = uniqid("user_", true);

        // Prepare the query
        $query = $this->database->prepare("INSERT INTO users (id, name, login, password, status) VALUES (:id, :name, :login, :password, :status)");
        // Execute the query
        $query->execute([
            "id" => $id,
            "name" => $data->getName(),
            "login" => $data->getLogin(),
            "password" => $data->getPassword(),
            "status" => $data->getStatus()
        ]);

        // Return the id
        return $id;
    }


    public function read(string $id): ?UserDb
    {
        // Prepare the query
        $query = $this->database->prepare("SELECT * FROM users WHERE id = :id");
        // Execute the query
        $query->execute([
            "id" => $id
        ]);

        // Fetch the result
        $result = $query->fetch();

        // Return the user
        return new UserDb($result["id"], $result["name"], $result["login"], $result["password"], $result["status"]);
    }

    public function update(string $id, $data): bool
    {
        // Prepare the query
        $query = $this->database->prepare("UPDATE users SET name = :name, login = :login, password = :password WHERE id = :id");
        // Execute the query
        $query->execute([
            "id" => $id,
            "name" => $data->getName(),
            "login" => $data->getLogin(),
            "password" => $data->getPassword()
        ]);

        // Return true if the user has been updated
        return $query->rowCount() > 0;
    }

    public function delete(string $id): bool
    {
        // Prepare the query
        $query = $this->database->prepare("DELETE FROM users WHERE id = :id");
        // Execute the query
        $query->execute([
            "id" => $id
        ]);

        // Return true if the user has been deleted
        return $query->rowCount() > 0;
    }

    public function readAll(): array
    {
        // Prepare the query
        $query = $this->database->prepare("SELECT * FROM users");
        // Execute the query
        $query->execute();

        // Fetch the result
        $result = $query->fetchAll();

        // Return the users
        return array_map(function ($user) {
            return new User($user["name"], $user["login"], $user["password"], $user["id"]);
        }, $result);
    }

    public function deleteAll(): bool
    {
        // Prepare the query
        $query = $this->database->prepare("DELETE FROM users");
        // Execute the query
        $query->execute();

        // Return true if the users have been deleted
        return $query->rowCount() > 0;
    }

    public function readByLogin(string $login): ?UserDb
    {
        // Prepare the query
        $query = $this->database->prepare("SELECT * FROM users WHERE login = :login");
        // Execute the query
        $query->execute([
            "login" => $login
        ]);

        // Fetch the result
        $result = $query->fetch();

        if ($result !== false) {
            // Return the user
            return new UserDb($result["id"], $result["name"], $result["login"], $result["password"], $result["status"]);
        } else {
            return null;
        }
    }

    /**
     * Count the number of events that a user has created.
     *
     * @param string $id The id of the user.
     * @return int The number of events.
     */
    public function countEvents(string $id): int
    {
        // Prepare the query
        $query = $this->database->prepare("SELECT COUNT(*) FROM events WHERE user_id = :id");

        // Execute the query
        $query->execute([
            "id" => $id
        ]);

        // Fetch the result
        $result = $query->fetch();

        // Return the number of events
        return $result[0];
    }


    public function count(): int
    {
        // Prepare the query
        $query = $this->database->prepare("SELECT COUNT(*) FROM users");

        // Execute the query
        $query->execute();

        // Fetch the result
        $result = $query->fetch();

        // Return the number of users
        return $result[0];
    }
}
