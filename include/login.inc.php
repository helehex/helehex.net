<?php

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if ((empty($username) || empty($password)) !== false) {
        header("Location: ../login.php?error=empty-input");
        exit();
    }

    $verified_user = login_user($conn, $username, $password);

    if ($verified_user === false) {
        header("Location: ../login.php?error=wrong-login");
        exit();
    }

    session_start();
    $_SESSION["user_id"] = $verified_user["user_id"];
    $_SESSION["user_name"] = $verified_user["user_name"];
    $_SESSION["user_image"] = $verified_user["user_image"];
    $_SESSION["user_rank"] = $verified_user["user_rank"];
    header("Location: ../index.php");
    exit();
} else {
    header("Location: ../login.php");
    exit();
}