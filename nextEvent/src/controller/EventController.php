<?php

namespace controller;

use exceptions\BadRequestException;
use exceptions\NotPageFoundException;
use model\event\EventBuilder;
use model\event\EventDb;
use utils\Input;
use App\Router;
use utils\Storage;
use vue\WebView;

/**
 * EventController is the controller of the events. It permits to do CRUD operations on events.
 * It uses the EventsLocalStorage to store events. It uses the EventView to display events.
 * It uses the Event to represent events.
 *
 * @author 22013393
 * @version 1.0
 */
class EventController extends Controller
{
    /**
     * The storage of the application.
     * @var Storage
     */
    private Storage $eventStorage;

    /**
     * The user storage of the application.
     * @var Storage
     */
    private Storage $userStorage;

    /**
     * The parameter of the controller.
     * @var ?string
     */
    private ?string $param;


    /**
     * Event builder.
     * @var ?EventBuilder
     */
    private ?EventBuilder $eventBuilder;

    /**
     * Event builder for update.
     */
    private ?EventBuilder $eventBuilderForUpdate;


    /**
     * The constructor of the event controller.
     *
     * @param Storage $storage The storage of the application.
     * @param WebView $eventView The event view.
     * @param ?string $param The parameter of the controller.
     */
    public function __construct(WebView $eventView, Storage $storage, Storage $userStorage, ?string $param)
    {
        parent::__construct($eventView);
        $this->eventStorage = $storage;
        $this->userStorage = $userStorage;
        $this->param = $param;
        $this->eventBuilder = $_SESSION['eventBuilder'] ?? null;
        $this->eventBuilderForUpdate = $_SESSION['eventBuilderForUpdate'] ?? null;
    }

    /**
     *  Destroy the event controller.
     */
    public function __destruct()
    {
        // Save the builder in the session.
        $_SESSION['eventBuilder'] = $this->eventBuilder;
        $_SESSION['eventBuilderForUpdate'] = $this->eventBuilderForUpdate;
    }


    /**
     * Display home page.
     */
    public function home(): void
    {
        $this->getWebView()->makeHomePage();
    }

    /**
     * Show about page.
     *
     * @return void
     */
    public function about(): void
    {
        $this->getWebView()->makeAboutPage();
    }

    //////////////////////////// CRUD OPERATIONS ON EVENTS ////////////////////////////

    /**
     * Display the events
     *
     * @return void
     */
    public function liste(): void
    {
        $result = [];
        $currentPage = 1;
        $limit = 5;
        $total = $this->eventStorage->count();
        $pages = ceil($total / $limit);

        // We check if the parameter is not null.
        if ($this->param !== null) {
            // We check if the parameter is a number.
            if (is_numeric($this->param)) {
                $currentPage = (int)$this->param;
            } else {
                // We throw an exception if the parameter is not a number.
                throw new BadRequestException("The parameter must be a number.");
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (array_key_exists('sort', $_POST) && array_key_exists('order', $_POST)) {
                // We get the sort and order parameters.
                $sort = $_POST['sort'];
                $order = $_POST['order'];

                // We sanitize the parameters.
                $sort = Input::sanitizeFullSpecialChars($sort);
                $order = Input::sanitizeFullSpecialChars($order);

                // We check if the parameters are valid.
                if (in_array($sort, ['name', 'date', "place", "dateCreation", "dateUpdate"])) {
                    $_SESSION['sort'] = $sort;
                }
                if (in_array($order, ['asc', 'desc'])) {
                    $_SESSION['order'] = $order;
                }
            }

        }

        // Get current sort and order.
        $sort = $_SESSION['sort'] ?? 'dateCreation';
        $order = $_SESSION['order'] ?? 'desc';

        // We get the events.
        $result = $this->eventStorage->sort($sort, $order, $currentPage, $limit);

        // We display the events.
        $this->getWebView()->makeEventsPage($result, $currentPage, $pages, $sort, $order);

    }

    /**
     * Display the result of the search.
     */
    public function search(): void
    {
        $search = "";
        $result = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (array_key_exists('search', $_POST)) {
                // We get the search parameter.
                $search = $_POST['search'];

                // We sanitize the parameter.
                $search = Input::sanitizeFullSpecialChars($search);
            }
        }
        if ($search !== "") {
            $result = $this->eventStorage->getEventsBySearch($search);
        }
        $this->getWebView()->makeResearchResultPage($result);
    }

    /**
     * Display the event
     *
     * @return void
     * @throws NotPageFoundException
     */
    public function show(): void
    {
        if ($this->param === null) {
            throw new NotPageFoundException();
        }
        // Get the event from the storage
        $event = $this->eventStorage->read($this->param);
        // If the event is not found
        if ($event === null) {
            // Use the event view to make the unknown event page
            $this->getWebView()->makeUnknownEventPage();
        } else {
            // Use the event view to make the event page
            $this->getWebView()->makeEventPage($this->param, $event);
        }
    }

    //////////////////////////// CREATE EVENT ////////////////////////////

    /**
     * Create an event.
     */
    public function create(): void
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->store();
        } else {
            $this->showCreateForm();
        }
    }

    /**
     * Created an event.
     *
     * @return void
     */
    protected function store(): void
    {
        // Retrieve only needed data
        $data = array_intersect_key($_POST, array_flip(EventBuilder::getNeededKeys()));

        // Filter the data by applying to each value Input::sanitizeFullSpecialChars
        $data = array_map(static fn($value) => Input::sanitizeFullSpecialChars($value), $data);

        // Create an event builder
        $this->eventBuilder = new EventBuilder($data, $_FILES[EventBuilder::$IMAGE_REF] ?? null);

        // If the event is valid
        if ($this->eventBuilder->isValid()) {

            // Create the event
            $event = $this->eventBuilder->build();

            $eventDb = EventDb::associate($_SESSION["user"]->getId(), $event);

            // Store the event
            $id = $this->eventStorage->create($eventDb);

            // destroy the event builder
            $this->eventBuilder = null;

            // Make confirmation creation page
            $this->getWebView()->displayEventCreationSuccessMessage($id);


        } else {
            // Use the event view to make the event creation page
            $this->getWebView()->displayEventCreationFailureMessage();
        }

    }

    /**
     * Show the form to create an event.
     *
     * @return void
     */
    protected function showCreateForm(): void
    {
        // If the event builder is not set
        if ($this->eventBuilder === null) {
            // Create a new event builder
            $this->eventBuilder = new EventBuilder();
        }
        // Use the event view to make the create event page
        $this->getWebView()->makeEventCreationPage($this->eventBuilder);
    }

    //////////////////////////// EDIT AN EVENT ////////////////////////////

    /**
     * Edit an event.
     * @throws NotPageFoundException
     */
    public function edit(): void
    {
        if ($this->param === null) {
            throw new NotPageFoundException();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update();
        } else {
            $this->showEditForm();
        }
    }

    /**
     * Update an event.
     *
     * @return void
     */
    protected function update(): void
    {
        // Retrieve only needed data
        $data = array_intersect_key($_POST, array_flip(EventBuilder::getNeededKeys()));

        // Filter the data by applying to each value Input::sanitizeFullSpecialChars
        $data = array_map(static fn($value) => Input::sanitizeFullSpecialChars($value), $data);

        // Create an event builder
        $this->eventBuilderForUpdate = new EventBuilder($data, $_FILES[EventBuilder::$IMAGE_REF] ?? null);

        // If the event is valid
        if ($this->eventBuilderForUpdate->isValid()) {

            // Create the event
            $event = $this->eventBuilderForUpdate->build();


            // Update the event
            $this->eventStorage->update($this->param, $event);

            // destroy the event builder
            $this->eventBuilderForUpdate = null;

            // Make confirmation creation page
            $this->getWebView()->displayEventEditionSuccessMessage($this->param);
        } else {
            // Use the event view to make the event edition page
            $this->getWebView()->displayEventEditionFailureMessage($this->param);
        }
    }

    /**
     * Show the form to edit an event.
     *
     */
    protected function showEditForm(): void
    {
        // Get the event from the storage
        $event = $this->eventStorage->read($this->param);

        // If the event is not found
        if ($event === null) {
            // Use the event view to make the unknown event page
            $this->getWebView()->makeUnknownEventPage();
        } else {
            
                // Create a new event builder
                $this->eventBuilderForUpdate = EventBuilder::fromEvent($event);
            

            // Use the event view to make the event edition page
            $this->getWebView()->makeEventEditionPage($this->eventBuilderForUpdate, $this->param);
        }
    }

    /////////////////////////////// DELETE ///////////////////////////////


    /**
     * Delete an event.
     * @throws NotPageFoundException
     */
    public function delete(): void
    {
        if ($this->param === null) {
            throw new NotPageFoundException();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // We delete the event
            $this->destroy();
        } else {
            // Use the event view to make the event deletion page
            $this->askDelete();
        }
    }

    /**
     * Delete an event.
     *
     */
    protected function destroy(): void
    {
        // Get the event from the storage
        $event = $this->eventStorage->read($this->param);

        // If the event is not found
        if ($event === null) {
            // Use the event view to make the unknown event page
            $this->getWebView()->displayEventDeletionErrorMessage();
        } else {
            // If the image is not the default image
            if ($event->getImage() !== ""){
                // Delete the image
                Router::deleteUploadFile("images/" . $event->getImage());
            }
            $this->eventStorage->delete($this->param);

            // Use the event view to make the event deletion confirmation page
            $this->getWebView()->displayEventDeletionSuccessMessage($this->param, $event->getName());
        }
    }

    /**
     *  Ask for confirmation to delete an event.
     *
     */
    protected function askDelete(): void
    {
        // Get the event from the storage
        $event = $this->eventStorage->read($this->param);

        // If the event is not found
        if ($event === null) {
            // Use the event view to make the unknown event page
            $this->getWebView()->makeUnknownEventPage();
        } else {
            // Use the event view to make the event deletion page
            $this->getWebView()->makeEventDeletionPage($this->param, $event->getName());
        }
    }


}
