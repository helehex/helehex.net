<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_rank"] < 85) {
    header("Location: ../downloads.php?error=not-authorized");
    exit();
}

if (isset($_POST["submit"])) {
    if (!isset($_POST["version"]) || !isset($_POST["changes"]) || !isset($_POST["title"])) {
        header("Location: ../downloads.php?error=empty-input");
        exit();
    }

    $version = $_POST["version"];
    $title = $_POST["title"];
    $changes = $_POST["changes"];

    if (empty($version) || empty($changes) || empty($title)) {
        header("Location: ../downloads.php?error=empty-input");
        exit();
    }

    if ($_FILES["windows"]["error"] === 0) {
        $windows = $_FILES["windows"];
    } else {
        $windows = false;
    }

    if ($_FILES["mac"]["error"] === 0) {
        $mac = $_FILES["mac"];
    } else {
        $mac = false;
    }

    if ($_FILES["linux"]["error"] === 0) {
        $linux = $_FILES["linux"];
    } else {
        $linux = false;
    }

    require_once '../../includes/dbh.inc.php';
    require_once '../../includes/functions.inc.php';

    upload_pointsim($conn, $version, $title, $changes, $windows, $mac, $linux);
    header("Location: ../downloads.php?error=none");
    exit();
} else {
    header("Location: ../downloads.php");
    exit();
}
