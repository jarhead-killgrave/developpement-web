<?php
/**
 * The template for the event show page.
 *
 */
?>
<section class="event show">
    <h2>
        <?= $event->getName() ?>
    </h2>
    <hr>
    <h3>
        Description
    </h3>
    <p>
        <?= $event->getDescription() ?>
    </p>
    <h3>
        Date
    </h3>
    <p>
        It will take place on <?= (new DateTime($event->getDate()))->format("d/m/Y") ?>
        at <?= (new DateTime($event->getDate()))->format("H:i") ?>
        <?php if (new DateTime($event->getDate()) < new DateTime()): ?>
            <span class="text-info"> (passed)</span>
        <?php endif; ?>
    </p>
    <h3>
        Place of the event
    </h3>
    <p>
        <?= $event->getPlace() ?>
    </p>
    <?php if ($event->getImage() !== ""): ?>
        <img src="<?= $this->getRouter()->getUploadFile("images/" . $event->getImage()) ?>" alt="Image de l'événement">
    <?php endif; ?>
    <hr>
    <section class="actions">
        <?php if ($this->user->getStatus() === "admin" || $this->user->getId() === $event->getUserId()): ?>
            <a href="<?= $this->getRouter()->getUpdateEventPage($id) ?>" class="btn btn-primary">Edit</a>
            <a href="<?= $this->getRouter()->getDeleteEventPage($id) ?>" class="btn btn-danger">Delete</a>
        <?php endif; ?>
    </section>
    <hr>
    <!-- Information about the date when the event was created  and the last time it was updated -->
    <p>
        Created on <?= (new DateTime($event->getDateCreation()))->format("d/m/Y") ?>
        at <?= (new DateTime($event->getDateCreation()))->format("H:i") ?>
    </p>
    <p>
        Last update on <?= (new DateTime($event->getDateUpdate()))->format("d/m/Y") ?>
        at <?= (new DateTime($event->getDateUpdate()))->format("H:i") ?>
    </p>
</section>
