<?php

namespace utils;

/**
 * Storage is an interface that permits to store data.
 *
 * The user can do different CRUD operations with the data.
 *
 * @author 22013393
 * @version 1.0
 */
interface Storage
{
    /**
     * Create a new item.
     *
     * @return mixed The id of the new item.
     */
    public function create($data): string;

    /**
     * Read an item.
     *
     * @param string $id The id of the item.
     * @return array|Object The item.
     */
    public function read(string $id);

    /**
     * Update an item.
     *
     * @param string $id The id of the item.
     * @return bool True if the item has been updated, false otherwise.
     */
    public function update(string $id, $data): bool;

    /**
     * Delete an item.
     *
     * @param string $id The id of the item.
     * @return bool True if the item has been deleted, false otherwise.
     */
    public function delete(string $id): bool;

    /**
     * Get all items.
     *
     * @return array The items.
     */
    public function readAll(): array;

    /**
     * Delete all items.
     *
     * @return bool True if the items have been deleted, false otherwise.
     */
    public function deleteAll(): bool;

    /**
     * Get the number of items.
     *
     * @return int The number of items.
     */
    public function count(): int;


}
