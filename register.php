<?php

session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

?>

<?php include_once 'layout/header.php'; ?>

<?php

if (isset($_GET["error"])) {
    if ($_GET["error"] == "whitelist") {
        ?>
        <div class="error">Registration is currently whitelisted</div><?php
    }

    if ($_GET["error"] == "empty-input") {
        ?>
        <div class="notice">Enter a Username and Password</div><?php
    }

    if ($_GET["error"] == "invalid-username") {
        ?>
        <div class="error">! Invalid Username !<br>( 3-20 letters or numbers )</div><?php
    }

    if ($_GET["error"] == "invalid-password") {
        ?>
        <div class="error">! Invalid Password !<br>( 3-20 characters )</div><?php
    }

    if ($_GET["error"] == "password-mismatch") {
        ?>
        <div class="error">! Password Mismatch !</div><?php
    }

    if ($_GET["error"] == "username-taken") {
        ?>
        <div class="error">! Username Taken !</div><?php
    }

    if ($_GET["error"] == "f") {
        ?>
        <div class="error">! Something went wrong !</div><?php
    }
} else {
    ?>
    <div class="notice">Note:<br>Password cannot be changed</div>
    <?php
}

?>

<form class="form" action="/includes/register.inc.php" method="post" autocomplete="off">
    <h1>Register</h1>
    <a class="right-button" href="/login.php">Login</a>
    <hr>
    <label class="textbox">Username<input type="text" maxlength="20" name="username"></label>
    <label class="textbox">Password<input type="password" maxlength="20" name="password"></label>
    <label class="textbox">Confirm Password<input type="password" maxlength="20" name="confirm-password"></label>
    <hr>
    <input class="center-button" type="submit" name="submit" value="Submit">
</form>

<?php include 'layout/footer.php'; ?>