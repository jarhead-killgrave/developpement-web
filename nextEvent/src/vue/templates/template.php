<?php
/**
 * The default page.
 *
 */

use utils\FormBuilder;

$formBuilder = new FormBuilder($this->getRouter()->getSearchResultPage(), "post");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php
    foreach ($this->getStyles() as $style) {
        echo "<link rel=\"stylesheet\" href='" . $this->getRouter()->getCssFile($style) . "'>";
    }
    ?>
    <script src=<?= $this->getRouter()->getJavascriptFile("feedback") ?>></script>
    <title><?= $this->getTitle() ?></title>
</head>
<body>
<header class="header">
    <!-- Internal search engine -->
    <form class="search" action="<?= $this->getRouter()->getSearchResultPage() ?>" method="POST">
        <input type="text" name="search" placeholder="Search">
        <input type="submit" value="Search">
    </form>

    <!-- Title -->
    <h1 class="title">
        Next Event
    </h1>

    <!-- Login / logout / register / account -->
    <div class="user">
        <?php if ($this->getRouter()->isLogged()): ?>
            <a class="link" href="<?= $this->getRouter()->getLogoutPage() ?>">Logout</a>
            <a class="link" href="<?= $this->getRouter()->getAccountPage() ?>">Account</a>
        <?php else: ?>
            <a class="link" href="<?= $this->getRouter()->getLoginPage() ?>">Login</a>
            <a class="link" href="<?= $this->getRouter()->getRegisterPage() ?>">Register</a>
        <?php endif; ?>
    </div>

</header>
<!-- End of the header -->

<!-- Menu navigation -->
<nav class="menu">
    <ul>
        <?php foreach ($this->getMenu() as $name => $item) { ?>
            <li>
                <a href="<?= $item["route"] ?>"
                   class="<?= $this->getRouter()->isActive($item["route"]) ? "active" : "" ?>">
                    <?= $name ?>
                </a>
            </li>
        <?php } ?>
    </ul>
</nav>
<!-- End of the menu navigation -->

<!-- Main content -->
<main class="main">
    <!-- The feedBack message -->
    <?php if ($this->getFeedBack() !== null) { ?>
        <p id="feedBack" class="feedBack-<?= $this->getFeedBack()["status"] ?>"></p>
    <?php } ?>

    <!-- Title of the page -->
    <h2><?= $this->getTitle() ?></h2>
    <!-- End of the title of the page -->

    <!-- Content of the page -->

    <div class="content">
        <?= $this->getContent() ?>
    </div>
    <!-- End of the content of the page -->

</main>
<footer class="footer">
    <p>Next Event &copy; 2022</p>
</footer>
<script>
    <?php if ($this->getFeedBack() !== null) { ?>
    showFeedback("<?= $this->getFeedBack()["message"] ?>");
    <?php } ?>
</script>
</body>

</html>


