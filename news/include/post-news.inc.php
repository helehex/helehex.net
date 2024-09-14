<?php

session_start();
require_once '../../includes/dbh.inc.php';
require_once '../../includes/functions.inc.php';

$sort = "new";
$room = "e";
$rank = 0;
$index = 0;

if (isset($_GET["sort"])) {
    $sort = trim(strtolower($_GET["sort"]));

    if ($sort !== "new" && $sort !== "old") {
        $sort = "new";
    }
}

if (isset($_GET["room"])) {
    $room = trim(strtolower($_GET["room"]));

    if (empty($room)) {
        $room = "e";
    }
}

if (isset($_GET["rank"])) {
    $rank = $_GET["rank"];

    if ($rank < 0) {
        $rank = 0;
    }
}

$get = "?sort=" . $sort . "&room=" . $room . "&rank=" . $rank;

if (isset($_POST["submit"])) {
    if (!isset($_SESSION["user_id"]) || $_SESSION["user_rank"] < $rank) {
        header("Location: ../index.php" . $get . "&error=rank");
        exit();
    }

    if (empty($_POST["title"]) && empty($_POST["body"])) {
        header("Location: ../index.php" . $get . "&error=empty");
        exit();
    }

    if (strlen($_POST["title"]) > 60 || substr_count($_POST["title"], "\n") > 0 || strlen($_POST["body"]) > 3000 || substr_count($_POST["body"], "\n") > 100) {
        header("Location: ../index.php" . $get . "&error=overflow");
        exit();
    } else {
        post_news($conn, $_SESSION["user_id"], $rank, $room, $_POST["title"], $_POST["body"]);
        header("Location: ../index.php" . $get);
        exit();
    }
} else {
    header("Location: ../news/index.php" . $get);
    exit();
}