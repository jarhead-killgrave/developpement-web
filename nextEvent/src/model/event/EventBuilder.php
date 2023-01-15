<?php

namespace model\event;

use DateTime;
use Exception;
use utils\Image;
use utils\Validator;

/**
 * EventBuilder is a class that permits to build events. It represents an event that is not yet stored.
 *
 * @author 22013393
 * @version 1.0
 */
class EventBuilder
{
    /**
     * The reference of the name.
     * @var string
     */
    public static string $NAME_REF = "name";

    /**
     * The reference of the description.
     * @var string
     */
    public static string $DESCRIPTION_REF = "description";

    /**
     * @var string The reference of the date.
     */
    public static string $DATE_REF = "date";

    /**
     * @var string The reference of the time.
     */
    public static string $TIME_REF = "time";

    /**
     * @var string The reference of the place.
     */
    public static string $PLACE_REF = "place";

    /**
     * @var string The reference of the path of the image.
     */
    public static string $IMAGE_REF = "image";


    /**
     * The data of the event.
     */
    private array $datas;

    /**
     * The image of the event.
     */
    private ?Image $image;

    /**
     * The errors of the event.
     */
    private array $errors;

    /**
     * The constructor of the event builder.
     *
     * @param array $data The data of the event.
     * @param ?array $image The image of the event.
     */
    public function __construct(array $data = [], array $image = null)
    {
        $this->datas = $data;
        $this->errors = [];
        if ($image !== null) {
            $this->image = new Image($image['name'], $image['type'], $image['size'], $image['tmp_name'], $image['error']);
        } else {
            $this->image = null;
        }


    }

    /**
     * Builder from an event.
     *
     * @param Event $event The event.
     * @return EventBuilder The event builder.
     */
    public static function fromEvent(Event $event): EventBuilder
    {
        $builder = new EventBuilder();
        $date = new DateTime($event->getDate());
        $builder->datas = [
            self::$NAME_REF => $event->getName(),
            self::$DESCRIPTION_REF => $event->getDescription(),
            self::$DATE_REF => $date->format("Y-m-d"),
            self::$TIME_REF => $date->format("H:i"),
            self::$PLACE_REF => $event->getPlace(),
        ];

        return $builder;
    }

    /**
     * Get the image of the event.
     * @return Image|null The image of the event.
     */
    public function getImage(): ?Image
    {
        return $this->image;
    }

    /**
     * Return the data of the event.
     *
     * @return array The data of the event.
     */
    public function getDatas(): array
    {
        return $this->datas;
    }

    /**
     * Return the errors of the event.
     *
     * @return array The errors of the event.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if the event is valid.
     *
     * @return bool True if the event is valid, false otherwise.
     */
    public function isValid(): bool
    {
        // Boolean that indicates if the event is valid
        $isValid = true;
        // Check if the name is valid
        if (Validator::isStringEmpty($this->getData(self::$NAME_REF))) {
            $this->errors[self::$NAME_REF] = "The name of the event is obligatory.";
        } // Check if the name not too long
        else if (strlen($this->getData(self::$NAME_REF)) > 255) {
            $this->errors[self::$NAME_REF] = "The name of the event must not exceed 255 characters.";
        } // Check if the name is not too short
        else if (strlen($this->getData(self::$NAME_REF)) < 3) {
            $this->errors[self::$NAME_REF] = "The name of the event must be at least 3 characters.";
        }
        // Check if the description is valid
        if (Validator::isStringEmpty($this->getData(self::$DESCRIPTION_REF))) {
            $this->errors[self::$DESCRIPTION_REF] = "The description of the event is obligatory.";
            $isValid = false;
        } // Check if the description must contain sufficient words
        else if (count(explode(" ", $this->getData(self::$DESCRIPTION_REF))) < 5) {
            $this->errors[self::$DESCRIPTION_REF] = "The description of the event must contain at least 5 words.";
            $isValid = false;
        }

        // Check if the date is valid
        if (Validator::isStringEmpty($this->getData(self::$DATE_REF))) {
            $this->errors[self::$DATE_REF] = "The date of the event is obligatory.";
            $isValid = false;
        } else if (!Validator::isDate($this->getData(self::$DATE_REF))) {
            $this->errors[self::$DATE_REF] = "The date of the event is not valid.";
            $isValid = false;
        } else if (!Validator::isDateNotInPast($this->getData(self::$DATE_REF))) {
            $this->errors[self::$DATE_REF] = "The date of the event must be in the future.";
            $isValid = false;
        }

        // Check if the time is valid
        if (Validator::isStringEmpty($this->getData(self::$TIME_REF))) {
            $this->errors[self::$TIME_REF] = "The time of the event is obligatory.";
            $isValid = false;
        } else if (!Validator::isTime($this->getData(self::$TIME_REF))) {
            $this->errors[self::$TIME_REF] = "The time of the event is not valid.";
            $isValid = false;
        } else if (!Validator::isTimeNotInPast($this->getData(self::$DATE_REF), $this->getData(self::$TIME_REF))) {
            $this->errors[self::$TIME_REF] = "The time of the event must be in the future.";
            $isValid = false;
        }

        // Check if the location is valid
        if (Validator::isStringEmpty($this->datas[self::$PLACE_REF] ?? "")) {
            $this->errors[self::$PLACE_REF] = "The location of the event is obligatory.";
            $isValid = false;
        }

        // Check if the image is valid
        if ($this->image !== null && $this->image->getError() !== UPLOAD_ERR_NO_FILE) {
            if ($this->image->getError() !== 0) {
                $this->errors[self::$IMAGE_REF] = "The image of the event is not valid.";
                $isValid = false;
            } else if (!Validator::isImage($this->image)) {
                $this->errors[self::$IMAGE_REF] = "This file is not an image.";
                $isValid = false;
            } else if (!Validator::isImageSizeValid($this->image, 1000000)) {
                $this->errors[self::$IMAGE_REF] = "The image of the event is too big.";
                $isValid = false;
            } else if (!Validator::isImageExtensionValid($this->image, ['jpg', 'jpeg', 'png'])) {
                $this->errors[self::$IMAGE_REF] = "You can only upload jpg, jpeg or png files.";
                $isValid = false;
            }


        }

        return $isValid;
    }

    /**
     * Return the data of the event.
     *
     * @param string $key The key of the data.
     * @return string The data of the event.
     */
    public function getData(string $key): string
    {
        return $this->datas[$key] ?? '';
    }

    /**
     * Return the error of the event.
     *
     * @param string $key The key of the error.
     * @return string The error of the event.
     */
    public function getError(string $key): string
    {
        return $this->errors[$key] ?? '';
    }

    /**
     * Check if the event has an error.
     *
     * @param string $key The key of the error.
     * @return bool True if the event has an error, false otherwise.
     */
    public function hasError(string $key): bool
    {
        return array_key_exists($key, $this->errors);
    }

    /**
     * Build the event.
     *
     * @return Event The event.
     * @throws Exception If the event is not valid.
     */
    public function build(): Event
    {
        // We check if all keys are present
        if (count(array_diff(self::getNeededKeys(), array_keys($this->datas))) > 0) {
            throw new Exception("Missing keys in the event.");
        }
        // Create and return the event
        $path = $this->image !== null ? $this->image->upload() : "";
        $date = $this->datas[self::$DATE_REF] . " " . $this->datas[self::$TIME_REF];
        return new Event(
            $this->datas[self::$NAME_REF],
            $this->datas[self::$DESCRIPTION_REF],
            $date,
            $this->datas[self::$PLACE_REF],
            $path
        );
    }

    /**
     * Return the needed keys of the event.
     *
     * @return array The needed keys of the event.
     */
    public static function getNeededKeys(): array
    {
        return [
            self::$NAME_REF,
            self::$DESCRIPTION_REF,
            self::$DATE_REF,
            self::$TIME_REF,
            self::$PLACE_REF
        ];
    }


}
