<?php

session_start();
require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

include_once 'layout/header.php';

?>

<span class="ends">oOo</span>

<?php

if (isset($_GET["error"])) {
    if ($_GET["error"] == "none") {
        ?>
        <div class="success">Success</div><?php
    }

    if ($_GET["error"] == "empty-input") {
        ?>
        <div class="notice">Enter a version and changelog</div><?php
    }

    if ($_GET["error"] == "not-authorized") {
        ?>
        <div class="error">! Not Authorized !</div>
        <?php
    }
}

?>

<div class="card">
    <h1>Downloads</h1>

    <?php

    if (isset($_SESSION["user_id"]) && $_SESSION["user_rank"] > 85) {
        echo "<section><a class='right-button mb20' href='/pointsimulator/upload-version.php'>Upload</a></section>";
    }

    ?>

    <section>
        <?php get_all_pointsim_versions($conn); ?>
    </section>
</div>
<span class="ends">.o.</span>

<?php include_once 'layout/footer.php'; ?>