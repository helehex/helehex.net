<?php

require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

if (isset($_POST["voter_name"])) {
    $voter_name = $_POST["voter_name"];
} else {
    $voter_name = "";
}

if (isset($_POST["world_version"])) {
    $world_version = $_POST["world_version"];
} else {
    $world_version = "%";
}

if (isset($_POST["search"])) {
    $search = $_POST["search"];
} else {
    $search = "";
}

if (isset($_POST["sort"])) {
    $sort = $_POST["sort"];
} else {
    $sort = "new";
}

if (isset($_POST["page"])) {
    $page = $_POST["page"];
} else {
    $page = 1;
}

search_worlds($conn, $voter_name, $world_version, $search, $sort, $page, 15);