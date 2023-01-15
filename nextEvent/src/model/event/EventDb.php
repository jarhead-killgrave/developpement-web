<?php

namespace model\event;

/**
 * EventDb is a representation of the event like it is stored in the database.
 *
 * @author 22013393
 * @version 1.0
 */
class EventDb extends Event
{


    /**
     * The user's id that created the event.
     *
     * @var string
     */
    private string $userId;

    /**
     * The constructor of the event.
     *
     * @param string $userId The user's id that created the event.
     * @param string $title The event's title.
     * @param string $description The event's description.
     * @param string $date The event's date.
     * @param string $place The event's place.
     * @param string $image The event's image.
     * @param string $dateCreation The event's creation date.
     * @param string $dateUpdate The event's update date.
     */
    public function __construct(string $userId, string $title, string $description, string $date, string $place,
                                string $image = "", string $dateCreation = "", string $dateUpdate = ""
    )
    {
        parent::__construct($title, $description, $date, $place, $image, $dateCreation, $dateUpdate);
        $this->userId = $userId;
    }

    /**
     * Associate the event to the user.
     *
     * @param string $userId The user's id.
     * @param Event $event The event.
     *
     * @return EventDb The event associated to the user.
     */
    public static function associate(string $userId, Event $event): EventDb
    {
        return new EventDb($userId, $event->getName(), $event->getDescription(), $event->getDate(),
            $event->getPlace(), $event->getImage()
        );
    }

    /**
     * Get the user's id that created the event.
     *
     * @return string The user's id that created the event.
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

}
