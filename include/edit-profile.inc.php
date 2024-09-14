<?php

session_start();

if (isset($_POST["submit"])) {
    $user_bio = $_POST["bio"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    set_user_bio($conn, $user_bio);
} else {
    header("Location: ../profile.php");
    exit();
}