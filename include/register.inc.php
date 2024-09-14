<?php

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm-password"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if ($username === " ") {
        header("Location: ../register.php?error=whitelist");
        exit();
    }

    if ((empty($username) || empty($password) || empty($confirm_password)) !== false) {
        header("Location: ../register.php?error=empty-input");
        exit();
    }

    if (invalid_username($username) !== false) {
        header("Location: ../register.php?error=invalid-username");
        exit();
    }

    if (invalid_password($password) !== false) {
        header("Location: ../register.php?error=invalid-password");
        exit();
    }

    if (password_mismatch($password, $confirm_password) !== false) {
        header("Location: ../register.php?error=password-mismatch");
        exit();
    }

    if (get_user_by_name($conn, $username) !== false) {
        header("Location: ../register.php?error=username-taken");
        exit();
    }

    create_user($conn, $username, $password);
} else {
    header("Location: ../register.php");
    exit();
}