<?php

namespace vue;

use model\event\Event;
use model\event\EventBuilder;
use model\user\AuthenticationManager;
use model\user\User;
use utils\FormBuilder;
use App\Router;

/**
 * The WebView is a class that permits to display the event page.
 *
 * @author 22013393
 * @version 1.0
 */
class EventView extends WebView
{
    /**
     * The connected user.
     *
     * @var ?string
     */
    private ?User $user;

    /**
     * The constructor of the event view.
     *
     * @param Router $router The router.
     * @param ?array $feedback The feedback.
     */
    public function __construct(Router $router, ?array $feedback = null)
    {
        parent::__construct($router, $feedback);
        $this->user = AuthenticationManager::getUser();

    }

    /**
     * Make About page.
     */
    public function makeAboutPage(): void
    {
        // The title of the page.
        $this->setTitle("About");
        $this->addStyle("about");

        ob_start();
        include "templates/about-page.php";
        $this->setContent(ob_get_clean());
    }

    /**
     * Make the form for the logout page.
     *
     * @return void
     */
    public function makeUserLogoutForm(): void
    {
        // Set the title of the page
        $this->setTitle("Logout");
        $form = new FormBuilder($this->getRouter()->getDisconnectPage(), "POST");
        $form->addSubmit("Logout");

        $content = "<p>Are you sure you want to log out ?</p>";
        $content .= $form->build();
        $this->setContent($content);

    }

    /**
     * Make events page.
     *
     * @param array $events The events.
     * @param int $currentPage The current page.
     * @param int $pages The number of pages.
     * @param string $currentSort The current sort.
     * @param string $currentOrder The current order.
     */
    public function makeEventsPage(array $events, int $currentPage, int $pages,
                                        string $currentSort, string $currentOrder): void
    {
        // Set the title of the page
        $this->setTitle("Events");
        $this->addStyle("event-list-page");

        // Buffering the content
        ob_start();
        include "templates/events/event-list-page.php";
        $content = ob_get_clean();

        $this->setContent($content);

    }


    /**
     * Make the event page.
     *
     * @param string $id The id of the event.
     * @param Event $event The event.
     * @return void The event page.
     */
    public function makeEventPage(string $id, Event $event): void
    {
        $this->setTitle("Events");
        $this->addStyle("event");
        ob_start();
        include "templates/events/event.php";
        $this->setContent(ob_get_clean());
    }

    /**
     * Make unknown event page.
     *
     * @return void
     */
    public function makeUnknownEventPage(): void
    {
        $this->setTitle("Unknown event");
        $this->setContent("<p>Unknown event</p>");
    }

    /**
     * Make the event creation page.
     *
     * @param EventBuilder $eventBuilder The event builder.
     * @return void
     */
    public function makeEventCreationPage(EventBuilder $eventBuilder): void
    {
        $this->setTitle("Add event");
        $this->makeEventFormPage($eventBuilder, $this->getRouter()->getCreateEventPage(), "create");


    }

    /**
     * Make the event form page.
     *
     * @param EventBuilder $eventBuilder The event builder.
     * @param string $action The action.
     * @param string $submitButton The submit button.
     */
    private function makeEventFormPage(EventBuilder $eventBuilder, string $action, string $submitButton): void
    {
        $this->addStyle("form");

        $form = new FormBuilder($action, "post");

        // Name field
        $form->addField(EventBuilder::$NAME_REF, "text", "Name", "Enter the event's name",
            $eventBuilder->getData(EventBuilder::$NAME_REF), "", "", "");

        // Description field
        $form->addField(EventBuilder::$DESCRIPTION_REF, "textarea", "Description", "Enter the event's description",
            $eventBuilder->getData(EventBuilder::$DESCRIPTION_REF), "", "", "3");

        // Date field
        $form->addField(EventBuilder::$DATE_REF, "date", "Date", "",
            $eventBuilder->getData(EventBuilder::$DATE_REF), "", "", "");

        // Time field
        $form->addField(EventBuilder::$TIME_REF, "time", "Time", "",
            $eventBuilder->getData(EventBuilder::$TIME_REF), "", "", ""
        );

        // Location field
        $form->addField(EventBuilder::$PLACE_REF, "text", "Location", "Enter the event's location",
            $eventBuilder->getData(EventBuilder::$PLACE_REF), "", "", "");

        // Image field
        $form->addField(EventBuilder::$IMAGE_REF, "file", "Image", "Select an image",
            $eventBuilder->getData(EventBuilder::$IMAGE_REF), "", "", "");

        // Submit button
        $form->addSubmit($submitButton);

        // Add errors
        $form->addErrors($eventBuilder->getErrors());

        // Add the form to the content
        $this->setContent($form->build());
    }

    /**
     * Make the research result page.
     *
     * @param array $events The events.
     * @return void
     */
    public function makeResearchResultPage(array $events): void
    {
        $this->setTitle("Research result");
        $this->addStyle("event");
        ob_start();
        include "templates/events/search-result.php";
        $this->setContent(ob_get_clean());
    }

    /**
     * Make the event edition page.
     *
     * @param EventBuilder $eventBuilder The event builder.
     * @param string $id The id of the event.
     * @return void
     */
    public function makeEventEditionPage(EventBuilder $eventBuilder, string $id): void
    {
        $this->setTitle("Edit event");
        $this->makeEventFormPage($eventBuilder, $this->getRouter()->getUpdateEventPage($id), "edit");
    }

    /**
     * Make event deletion page.
     *
     * @param string $id The id of the event.
     * @param string $name The name of the event.
     * @return void
     */
    public function makeEventDeletionPage(string $id, string $name): void
    {
        $this->setTitle("Delete an event");
        $this->addStyle("form");
        ob_start();
        include "templates/events/event-deletion-form.php";
        $this->setContent(ob_get_clean());
    }


    /**
     * Display the success message for the creation of the event.
     *
     * @param string $id The id of the event.
     * @return void
     */
    public function displayEventCreationSuccessMessage(string $id): void
    {
        $this->getRouter()->postRedirectGet(
            $this->getRouter()->getEventListPage(), ["message" => "Event created successfully", "status" => "success"]);
    }

    /**
     * Display the success message for the edition of the event.
     *
     * @param string $id The id of the event.
     * @return void
     */
    public function displayEventEditionSuccessMessage(string $id): void
    {
        $this->getRouter()->postRedirectGet(
            $this->getRouter()->getEventPage($id), ["message" => "Event edited successfully", "status" => "success"]);

    }

    /**
     * Display the failure message for the edition of the event.
     *
     * @param string $id The id of the event.
     */
    public function displayEventEditionFailureMessage(string $id): void
    {
        $this->getRouter()->postRedirectGet(
            $this->getRouter()->getUpdateEventPage($id), ["message" => "Event edition failed", "status" => "error"]);
    }

    /**
     * Display the success message for the deletion of the event.
     *
     */
    public function displayEventDeletionSuccessMessage(): void
    {
        $this->getRouter()->postRedirectGet(
            $this->getRouter()->getEventListPage(), ["message" => "Event deleted successfully", "status" => "success"]);
    }

    /**
     * Display the error message for the deletion of the event.
     *
     */
    public function displayEventDeletionErrorMessage(): void
    {
        $this->getRouter()->postRedirectGet(
            $this->getRouter()->getEventListPage(), ["message" => "Error while deleting the event", "status" => "error"]);
    }

    /**
     * Display the error message for the creation of the event.
     *
     */
    public function displayEventCreationFailureMessage(): void
    {
        $this->getRouter()->postRedirectGet(
            $this->getRouter()->getCreateEventPage(), ["message" => "Error while creating the event", "status" => "error"]);
    }


    /**
     * Return the menu.
     *
     * @return array The menu.
     */
    public function getMenu(): array
    {
        $items = [];
        if (AuthenticationManager::isConnected()) {
            $items["Add an event"] = [
                "route" => $this->getRouter()->getCreateEventPage(),
                "active" => false
            ];
        }
        return parent::getMenu() + $items;
    }


}

