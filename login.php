<?php

session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include_once 'layout/header.php';

?>

<?php

if (isset($_GET["error"])) {
    if ($_GET["error"] == "none") {
        ?>
        <div class="success">Success</div><?php
    }

    if ($_GET["error"] == "empty-input") {
        ?>
        <div class="notice">Enter a Username and Password</div><?php
    }

    if ($_GET["error"] == "wrong-login") {
        ?>
        <div class="error">Wrong login</div>
        <?php
    }
}

?>

<form class="form" action="/includes/login.inc.php" method="post" autocomplete="off">
    <h1>Login</h1>
    <a class="right-button" href="/register.php">Register</a>
    <hr>
    <label class="textbox">Username<input type="text" maxlength="20" name="username"></label>
    <label class="textbox">Password<input type="password" maxlength="20" name="password"></label>
    <hr>
    <input class="center-button" type="submit" name="submit" value="Submit">
</form>

<?php include 'layout/footer.php'; ?>