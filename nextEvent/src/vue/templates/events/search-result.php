<?php

/**
 * Content of the result page of the search.
 * We list the urls of the events that match the search with the search term in bold.
 * @author 22013393
 */
?>
<section class="search-result">
    <ul>
        <?php foreach ($events as $id => $event) { ?>
            <li>
                <a class="link" href="<?= $this->getRouter()->getEventPage($id) ?>">
                    <?= $event->getName() ?>
                </a>
            </li>
        <?php } ?>

    </ul>
</section>



