<?php

session_start();
require_once '../../includes/dbh.inc.php';
require_once '../../includes/functions.inc.php';

$return_to;

if (isset($_GET["back"])) {
    $return_to = $_GET["back"];
} else {
    $return_to = "/index.php";
}

if (isset($_GET["id"]) && isset($_SESSION["user_id"])) {
    remove_news($conn, $_GET["id"]);
    header("Location: http://helehex.net" . $return_to);
    exit();
} else {
    header("Location: http://helehex.net" . $return_to);
    exit();
}