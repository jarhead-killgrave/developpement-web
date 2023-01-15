<?php

namespace model\event;

use Exception;
use lib\ObjectFileDB;
use utils\Storage;

/**
 * EventStorageFile is a class that permits to manage the storage of the events. It uses a file to store the events.
 *
 * @author 22013393
 * @version 1.0
 */
class EventStorageFile implements Storage
{


    /**
     * @var ObjectFileDB The file database.
     */
    private ObjectFileDB $storage;

    /**
     * The constructor of the event storage file.
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
     * Reinitialize the storage with the default events.
     *
     * @return bool True if the events have been reinitialized, false otherwise.
     */
    public function reinitialize(): bool
    {
        // try to reinitialize the storage
        try {
            $json = file_get_contents("database/events.json");
            $events = json_decode($json, true);

            // delete all events
            $this->deleteAll();
            // insert all events
            foreach ($events as $event) {
                $this->create(EventDb::associate("", Event::fromArray($event)));
            }
            return true;
        } catch (Exception $e) {
            // if problem during the reinitialization
            return false;
        }
    }

    /**
     * Delete all events.
     *
     * @return bool True if the events have been deleted, false otherwise.
     */
    public function deleteAll(): bool
    {
        // try to delete all events
        try {
            $this->storage->deleteAll();
            return true;
        } catch (Exception $e) {
            // if problem during the deletion
            return false;
        }
    }

    /**
     * Create an event.
     *
     * @param EventDb $data The event.
     * @return string The id of the event.
     */
    public function create($data): string
    {
        return $this->storage->insert($data);
    }

    /**
     * Update an event.
     *
     * @param string $id The id of the event.
     * @param Event $data The event.
     * @return bool True if the event has been updated, false otherwise.
     */
    public function update(string $id, $data): bool
    {
        // try to update the event
        try {
            $eventFromDb = $this->eventStorage->read($this->param);

            $eventDb = EventDb::associate($eventFromDb->getUserId(), $data);

            $eventDb->setCreationDate($eventFromDb->getDateCreation());
            if ($data->getImage() === "") {
                $eventDb->setImage($eventFromDb->getImage());
            }
            $this->storage->update($id, $eventDb);
            return true;
        } catch (Exception $e) {
            // if the event is not found or problem during the update
            return false;
        }
    }

    /**
     *  Read an event.
     *
     * @param string $id The id of the event.
     * @return ?EventDb The event.
     */
    public function read(string $id): ?EventDb
    {
        // try to get the event
        try {
            return $this->storage->fetch($id);
        } catch (Exception $e) {
            // if the event is not found
            return null;
        }
    }

    /**
     * Delete an event.
     *
     * @param string $id The id of the event.
     * @return bool True if the event has been deleted, false otherwise.
     */
    public function delete(string $id): bool
    {
        // try to delete the event
        try {
            $this->storage->delete($id);
            return true;
        } catch (Exception $e) {
            // if the event is not found or problem during the deletion
            return false;
        }
    }

    public function count(): int
    {
        return count($this->storage->fetchAll());
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
        // get all events
        $events = $this->readAll();
        // sort the events according to the sort and conserve the keys
        if ($sort !== "") {
            uasort($events, function ($a, $b) use ($sort) {
                $method = "get" . ucfirst($sort);
                return strcmp($a->$method(), $b->$method());
            });
        }
        // if the order is descending, reverse the array
        if ($order === "desc") {
            // We reverse and conserve the keys
            $events = array_reverse($events, true);
        }
        // get the events of the current page
        return array_slice($events, ($currentPage - 1) * $perPage, $perPage);
    }

    /**
     * Read all events.
     *
     * @return array The events.
     */
    public function readAll(): array
    {
        return $this->storage->fetchAll();
    }

    public function getEventsBySearch($search): array
    {
        $events = $this->readAll();
        $search = strtolower($search);
        $eventsBySearch = [];
        foreach ($events as $id => $event) {
            if (stripos($event->getName(), $search) !== false) {
                $eventsBySearch[$id] = $event;
            } else if (stripos($event->getDescription(), $search) !== false) {
                $eventsBySearch[$id] = $event;
            } else if (stripos($event->getPlace(), $search) !== false) {
                $eventsBySearch[$id] = $event;
            }
        }
        return $eventsBySearch;
    }


}
