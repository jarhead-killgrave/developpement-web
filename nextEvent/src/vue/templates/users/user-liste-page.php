<?php

/**
 * Content of the user list page that permits to display all the users in a table.
 *
 * @author 22013393
 * @version 1.0
 */

?>

<?php if (count($users) > 0): ?>
    <table class="user-table">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Login</th>
            <th scope="col">Status</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->getName() ?></td>
                <td><?= $user->getLogin() ?></td>
                <td><?= $user->getStatus() ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>There is no user.</p>
<?php endif; ?>


