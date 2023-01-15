<?php

namespace model\event;

use utils\Storage;

/**
 * EventStorageStub is a class that permits to manage the storage of the events.
 *
 * @author 22013393
 * @version 1.0
 */
class EventStorageStub implements Storage
{
    /**
     * The events.
     *
     * @var array
     */
    private array $events;

    /**
     * The constructor of the class.
     */
    public function __construct()
    {
        $this->events = [];
    }

    public function read(string $id)
    {
        return $this->events[$id] ?? null;
    }

    public function update(string $id, $data): bool
    {
        if (array_key_exists($id, $this->events)) {
            $this->events[$id]->setName($data['name']);
            $this->events[$id]->setDescription($data['description']);
            $this->events[$id]->setDate($data['date']);
            $this->events[$id]->setTime($data['time']);
            $this->events[$id]->setPlace($data['place']);
            return true;
        }
        return false;
    }

    public function delete(string $id): bool
    {
        if (array_key_exists($id, $this->events)) {
            unset($this->events[$id]);
            return true;
        }
        return false;
    }

    public function deleteAll(): bool
    {
        $this->events = [];
        return true;
    }

    /**
     * Initialize the storage from a json file.
     *
     * @param string $filename The name of the json file.
     */
    public function initFromJson(string $filename): void
    {
        $json = file_get_contents($filename);
        $events = json_decode($json, true);
        foreach ($events as $event) {
            $this->create(Event::fromArray($event));
        }
    }

    public function create($data): string
    {
        $this->events[] = $data;
        return count($this->events) - 1;
    }

    public function count(): int
    {
        return count($this->events);
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
        // sort the events according to the sort
        if ($sort !== "") {
            usort($events, function ($a, $b) use ($sort) {
                $method = "get" . ucfirst($sort);
                return strcmp($a->$method(), $b->$method());
            });
        }
        // if the order is descending, reverse the array
        if ($order === "desc") {
            $events = array_reverse($events);
        }
        // get the events of the current page
        return array_slice($events, ($currentPage - 1) * $perPage, $perPage);
    }

    public function readAll(): array
    {
        return $this->events;
    }
}
