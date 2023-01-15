<?php
/**
 * The template for the event list page.
 */

use utils\Str;

// The number of items per page.

$sortedKeys = [
        "name" => "Name",
        "date" => "Date",
        "dateCreation" => "Date of creation",
        "dateUpdate" => "Date of update",
        "place" => "Place"
]

?>
<div class="event-list">
    <div class="affichage">
        <form action="<?= $this->getRouter()->getEventListPage() ?>" method="post">
            <fieldset>
                <legend>Sort</legend>
                <label for="sort">Sort : <select name="sort" id="sort">
                        <?php foreach ($sortedKeys as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $key === $currentSort ? "selected" : "" ?>><?= $value ?></option>
                        <?php endforeach; ?>
                    </select></label>
                <label for="order">Order : <select name="order" id="order">
                        <?php foreach (["asc" => "Ascending", "desc" => "Descending"] as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $key === $currentOrder ? "selected" : "" ?>><?= $value ?></option>
                        <?php endforeach; ?>
                    </select></label>
                <input type="submit" value="Sort">
            </fieldset>
        </form>
    </div>
    <div class="events">
        <?php foreach ($events as $id => $event): ?>
            <a href="<?= $this->getRouter()->getEventPage($id) ?>">
                <div class="event">
                    <!-- Nom et image de l'événement -->
                    <section class="event-header">
                        <h3>
                            <?= $event->getName() ?>
                        </h3>
                        <?php if ($event->getImage() !== ""): ?>
                            <img src="<?= $this->getRouter()->getUploadFile("images/" . $event->getImage()) ?>"
                                 alt="Image de l'événement">
                        <?php else: ?>
                            <img src="<?= $this->getRouter()->getImageFile("unknownEvent.png") ?>"
                                 alt="Image de l'événement">
                        <?php endif; ?>
                    </section>
                    <!-- Description partielle de l'événement -->
                    <p class="event-description">
                        <?= Str::truncate($event->getDescription(), 125, "...") ?>
                    </p>
                    <hr>
                    <!-- Last update date -->
                    <p class="event-footer">
                        Last update : The <?= (new DateTime($event->getDateUpdate()))->format("d/m/Y") ?>
                        at <?= (new DateTime($event->getDateUpdate()))->format("H:i") ?>
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
        <!-- Pagination -->
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <button class="previous btn btn-primary"
                        onclick="window.location.href='<?= $this->getRouter()->getEventListPage($currentPage - 1) ?>'">
                    Previous
                </button>
            <?php endif; ?>
            <?php if ($currentPage < $pages): ?>
                <button class="next btn btn-primary"
                        onclick="window.location.href='<?= $this->getRouter()->getEventListPage($currentPage + 1) ?>'">
                    Next
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
