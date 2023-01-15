<?php
/**
 * The form for the deletion of an event.
 *
 */
?>

<form class="form" action="<?= $this->getRouter()->getDeleteEventPage($id) ?>" method="post">
    <p>Are you sure you want to delete the event "<?= $name ?>"?</p>
    <button class="btn btn-danger" type="submit">Delete</button>
    <button class="btn" type="button">
        <a href="<?= $this->getRouter()->getEventPage($id) ?>">Cancel</a>
    </button>
</form>

