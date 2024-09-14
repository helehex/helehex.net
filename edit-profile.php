<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include_once 'layout/header.php';
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';
?>

<span class="ends">iUi</span>
<form class="profile" action="/includes/edit-profile.inc.php" method="post" autocomplete="off">
    <div class="left">
        <button class="user">
            <img src=<?php echo "\"/images/profile/" . $_SESSION["user_image"] . "\""; ?>>
        </button>
    </div>
    <div class="right">
        <span class="username"><?php echo htmlspecialchars($_SESSION["user_name"]); ?></span>
        <input class="right-button" type="submit" name="submit" value="Save">
        <hr>
        <textarea name="bio" maxlength="1024"
            cols="50"><?php echo htmlspecialchars(get_user_bio($conn, $_SESSION["user_id"])); ?></textarea>
    </div>
</form>
<span class="ends">.i.</span>

<?php include 'layout/footer.php'; ?>