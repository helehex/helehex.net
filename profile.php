<?php

session_start();

if (!isset($_GET["user"]) && !isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include_once 'layout/header.php';
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

?>

<?php

if (isset($_GET["user"])) {
    $user = get_user_by_name($conn, $_GET["user"]);

    if ($user === false) {
        ?>
        <span class="ends">iUi</span>
        <span class="ends">Invalid User</span>
        <?php
    } else {
        ?>
        <span class="ends">iUi</span>
        <div class="profile">
            <div class="left">
                <div class="user">
                    <img src=<?php echo "\"/images/profile/" . $user["user_image"] . "\""; ?>>
                </div>
            </div>
            <div class="right">
                <span class="username selectable"><?php echo htmlspecialchars($user["user_name"]); ?></span>
                <hr>
                <p class="selectable"><?php echo htmlspecialchars(get_user_bio($conn, $user["user_id"])); ?></p>
            </div>
        </div>
        <span class="ends">.i.</span>
        <?php
    }
} elseif (isset($_SESSION["user_id"])) {
    ?>
    <span class="ends">iUi</span>
    <div class="profile">
        <div class="left">
            <a href="/includes/roll-profile.inc.php">
                <div class="user">
                    <img src=<?php echo "\"/images/profile/" . $_SESSION["user_image"] . "\""; ?>>
                </div>
            </a>
        </div>
        <div class="right">
            <span class="username selectable"><?php echo htmlspecialchars($_SESSION["user_name"]); ?></span>
            <a class="right-button" href="/edit-profile.php">Edit</a>
            <hr>
            <p class="selectable"><?php echo htmlspecialchars(get_user_bio($conn, $_SESSION["user_id"])); ?></p>
        </div>
    </div>
    <span class="ends">.i.</span>
    <?php
}

?>

<?php include 'layout/footer.php'; ?>