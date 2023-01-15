<?php

namespace model\event;

use PDO;
use utils\Storage;

/**
 * EventStorageFile is a class that permits to manage the storage of the events. It uses a file to store the events.
 *
 * @author 22013393
 * @version 1.0
 */
class EventStorageMySQL implements Storage
{

    /**
     * The database connection
     *
     * @var PDO
     */
    private PDO $database;

    /**
     * Create a new EventStorageSQL
     *
     * @param PDO $database
     */
    public function __construct($database)
    {
        //$this->database = new PDO("mysql:host=mysql.info.unicaen.fr;port=3306;dbname=22013393_dev;charset=utf8mb4", "22013393", "uPooqu5etheshiCh");
        $this->database = $database;
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Reinitialize the database if it does not exist
        if (!$this->database->query("SHOW TABLES LIKE 'events'")->fetch()) {
            $this->reinitialize();
        }
    }

    /**
     * Reinitialize the storage with the default events.
     *
     * @return bool True if the events have been reinitialized, false otherwise.
     */
    public function reinitialize(): bool
    {
        // Drop the table
        $requete = "DROP TABLE IF EXISTS events;";
        $this->database->query($requete);
        // Create the table
        $requete = "CREATE TABLE events (
            id VARCHAR(255) NOT NULL UNIQUE,
            name VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            date DATETIME NOT NULL,
            place VARCHAR(255) NOT NULL,
            image VARCHAR(255) NOT NULL DEFAULT '',
            dateCreation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            dateUpdate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            user_id VARCHAR(255) NOT NULL DEFAULT '',
            PRIMARY KEY (id)
        );";

        $this->database->query($requete);

        // Insert the default events from json file
        $json = file_get_contents("database/events.json");
        $events = json_decode($json, true);
        $events = array_map(function ($event) {
            $event = Event::fromArray($event);
            return EventDb::associate("", $event);
        }, $events);

        foreach ($events as $event) {
            $this->create($event);
        }


        return true;
    }

    /**
     * Create an event.
     *
     * @return string The id of the event.
     */
    public function create($data): string
    {
        // Generate an unique id
        $id = uniqid("event_", true);

        // Insert the event in the database
        $query = $this->database->prepare("INSERT INTO events (id, name, description, date, place, image, dateCreation, dateUpdate, user_id) VALUES (:id, :name, :description, :date, :place, :image, :dateCreation, :dateUpdate, :user_id)");

        $query->execute([
            "id" => $id,
            "name" => $data->getName(),
            "description" => $data->getDescription(),
            "date" => $data->getDate(),
            "place" => $data->getPlace(),
            "image" => $data->getImage(),
            "dateCreation" => $data->getDateCreation(),
            "dateUpdate" => $data->getDateUpdate(),
            "user_id" => $data->getUserId()
        ]);


        return $id;
    }

    /**
     *  Read an event.
     *
     * @param string $id The id of the event.
     * @return ?Event The event.
     */
    public function read(string $id): ?Event
    {
        $statement = $this->database->prepare("SELECT * FROM events WHERE id = :id");


        $statement->execute(["id" => $id]);


        $event = $statement->fetch(PDO::FETCH_ASSOC);

        // If the event exists in the database return it
        if ($event !== false) {
            return new EventDb($event["user_id"], $event["name"], $event["description"], $event["date"], $event["place"], $event["image"], $event["dateCreation"], $event["dateUpdate"]);
        }
        // Else return null
        return null;

    }

    /**
     * Read all events.
     *
     * @return array The events.
     */
    public function readAll(): array
    {
        $events = [];
        $query = $this->database->query("SELECT * FROM events");
        while ($event = $query->fetch()) {
            $events[$event["id"]] = new Event($event["name"], $event["description"], $event["date"], $event["place"], $event["image"], $event["dateCreation"], $event["dateUpdate"]);
        }
        return $events;
    }

    /**
     * Update an event.
     *
     * @param string $id The id of the event.
     * @param Event $data The new data of the event.
     * @return bool True if the event has been updated, false otherwise.
     */
    public function update(string $id, $data): bool
    {
        $request = "UPDATE events SET name = :name, description = :description, date = :date, place = :place";
        $params = [
            "id" => $id,
            "name" => $data->getName(),
            "description" => $data->getDescription(),
            "date" => $data->getDate(),
            "place" => $data->getPlace()
        ];
        if ($data->getImage() !== "") {
            $request .= ", image = :image";
            $params["image"] = $data->getImage();
        }
        $request .= " WHERE id = :id";
        $query = $this->database->prepare($request);

        $query->execute($params);

        return $query->rowCount() > 0;

    }


    /**
     * Delete an event.
     *
     * @param string $id The id of the event.
     * @return bool True if the event has been deleted, false otherwise.
     */
    public function delete(string $id): bool
    {
        $statement = $this->database->prepare("DELETE FROM events WHERE id = :id");
        $statement->execute(["id" => $id]);
        return $statement->rowCount() > 0;

    }

    /**
     * Delete all events.
     *
     * @return bool True if the events have been deleted, false otherwise.
     */
    public function deleteAll(): bool
    {
        $this->database->query("DELETE FROM events");
        return true;
    }

    /**
     * Get the events of a user.
     *
     * @param string $user_id The id of the user.
     * @return array The events.
     */
    public function getEventsOfUser(string $user_id): array
    {
        $events = [];
        $query = $this->database->prepare("SELECT * FROM events WHERE user_id = :user_id");
        $query->execute(["user_id" => $user_id]);
        while ($event = $query->fetch()) {
            $events[$event["id"]] = new Event($event["name"], $event["description"], $event["date"], $event["place"], $event["image"]);
        }
        return $events;
    }

    /**
     * Get the events that contains a search.
     *
     * @param string $search The search.
     * @return array The events.
     */
    public function getEventsBySearch(string $search): array
    {
        $events = [];
        // We set search to lower case
        $search = strtolower($search);
        $query = $this->database->prepare("SELECT * FROM events WHERE name LIKE :search OR description LIKE :search OR place LIKE :search");
        $query->execute(["search" => "%" . $search . "%"]);
        while ($event = $query->fetch()) {
            $events[$event["id"]] = new Event($event["name"], $event["description"], $event["date"], $event["place"], $event["image"]);
        }
        return $events;
    }

    /**
     * Get all events sorted and ordered.
     *
     * @param string $sort The sort.
     * @param string $order The order.
     * @param int $currentPage The current page.
     * @param int $perPage The number of events per page.
     * @return array The events.
     */
    public function sort(string $sort, string $order, int $currentPage, int $perPage): array
    {
        $events = [];
        $request = "SELECT * FROM events";
        if ($sort !== "") {
            $request .= " ORDER BY " . $sort;
            if ($order !== "") {
                $request .= " " . $order;
            }
        }
        $request .= " LIMIT " . (($currentPage - 1) * $perPage) . ", " . $perPage;

        $query = $this->database->query($request);
        while ($event = $query->fetch()) {
            $events[$event["id"]] = new Event($event["name"], $event["description"], $event["date"], $event["place"], $event["image"], $event["dateCreation"], $event["dateUpdate"]);
        }

        return $events;
    }

    /**
     * Get the number of events.
     *
     * @return int The number of events.
     */
    public function count(): int
    {
        $query = $this->database->query("SELECT COUNT(*) FROM events");
        return $query->fetchColumn();
    }


}
