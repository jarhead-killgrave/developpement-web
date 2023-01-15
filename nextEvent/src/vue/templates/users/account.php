<?php

/**
 * The template of the account page.
 */

?>

<section class="account">
    <h2>Mon compte</h2>
    <section>
        <h3>Informations personnelles</h3>
        <p>
            Nom : <strong><?= $user->getName() ?></strong><br>
            Login : <strong><?= $user->getLogin() ?></strong><br>
        </p>
    </section>
    <section>
        <h3>Informations sur les événements</h3>
        <p>
            Nombre d'événements créés : <?= $nbPosts ?><br>
        </p>
    </section>
</section>
