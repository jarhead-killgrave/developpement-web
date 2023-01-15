<?php

namespace model\event;

use JsonException;
use RuntimeException;

/**
 * The event is a class that permits to manage an event.
 *
 * @author 22013393
 * @version 1.0
 */
class Event
{
    /**
     * The name of the event.
     *
     * @var string
     */
    private string $name;

    /**
     * The description of the event.
     *
     * @var string
     */
    private string $description;

    /**
     * The date of the event.
     *
     * @var string
     */
    private string $date;

    /**
     * The place of the event.
     *
     * @var string
     */
    private string $place;

    /**
     * The path of the image of the event.
     *
     * @var string
     */
    private string $image;

    /**
     * The date creation of the event.
     *
     * @var string
     */
    private string $dateCreation;

    /**
     * The date update of the event.
     *
     * @var string
     */
    private string $dateUpdate;


    /**
     * Constructor of the event.
     *
     * @param string $name
     * @param string $description
     * @param string $date
     * @param string $place
     * @param string $image
     * @param string $dateCreation
     * @param string $dateUpdate
     */
    public function __construct(string $name, string $description, string $date, string $place, string $image = "", string $dateCreation = "", string $dateUpdate = "")
    {
        $this->name = $name;
        $this->description = $description;
        $this->date = $date;
        $this->place = $place;
        $this->image = $image;
        $this->dateCreation = $dateCreation === "" ? date("Y-m-d H:i:s") : $dateCreation;
        $this->dateUpdate = $dateUpdate === "" ? date("Y-m-d H:i:s") : $dateUpdate;
    }

    /**
     * Event from JSON.
     *
     * @param string $json
     * @return Event
     * @throws JsonException
     */
    public static function fromJSON(string $json): Event
    {
        try {
            $event = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new JsonException("The JSON is not valid.");
        }
        return self::fromArray($event);
    }

    /**
     * Event from array.
     *
     * @param array $event
     * @return Event
     */
    public static function fromArray(array $event): Event
    {
        // If the array is not valid, throw an exception.
        if (array_key_exists("name", $event) && array_key_exists("description", $event) &&
            array_key_exists("date", $event) && array_key_exists("place", $event)) {
            $eventModel = new Event($event["name"], $event["description"], $event["date"], $event["place"]);
            $eventModel->image = $event["image"] ?? "";
            $eventModel->dateCreation = $event["dateCreation"] ?? date("Y-m-d H:i:s");
            $eventModel->dateUpdate = $event["dateUpdate"] ?? date("Y-m-d H:i:s");
            return $eventModel;
        }

        throw new RuntimeException("The array is not valid.");
    }

    /**
     * Get the name of the event.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the event.
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->setUpdateDate();
    }

    /**
     * Set the update date of the event.
     *
     * @return void
     */
    public function setUpdateDate(): void
    {
        $this->dateUpdate = date("Y-m-d H:i:s");
    }

    /**
     * Set thhe creation date of the event.
     *
     * @param string $dateCreation
     */
    public function setCreationDate(string $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * Get the description of the event.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the description of the event.
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
        $this->setUpdateDate();
    }

    /**
     * Get the date of the event.
     *
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Set the date of the event.
     *
     * @param string $date
     * @return void
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
        $this->setUpdateDate();
    }

    /**
     * Get the place of the event.
     *
     * @return string
     */
    public function getPlace(): string
    {
        return $this->place;
    }

    /**
     * Set the place of the event.
     *
     * @param string $place
     * @return void
     */
    public function setPlace(string $place): void
    {
        $this->place = $place;
        $this->setUpdateDate();
    }

    /**
     * Get the date creation of the event.
     *
     * @return string
     */
    public function getDateCreation(): string
    {
        return $this->dateCreation;
    }

    /**
     * Get the date update of the event.
     *
     * @return string
     */
    public function getDateUpdate(): string
    {
        return $this->dateUpdate;
    }

    /**
     * Get the image of the event.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set the image of the event.
     *
     * @param string $image
     * @return void
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
        $this->setUpdateDate();
    }

    /**
     * Get the event in a string.
     *
     * @return string
     * @throws JsonException
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Get the event in a JSON.
     *
     * @return string
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * Get the event in an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            "name" => $this->name,
            "description" => $this->description,
            "date" => $this->date,
            "place" => $this->place,
            "dateCreation" => $this->dateCreation,
            "dateUpdate" => $this->dateUpdate,
            "image" => $this->image
        ];
    }


}
