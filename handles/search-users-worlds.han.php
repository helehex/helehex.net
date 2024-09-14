<?php

require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

if (isset($_POST["user_name"])) {
    $user_name = $_POST["user_name"];

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

    if (isset($_POST["page"])) {
        $page = $_POST["page"];
    } else {
        $page = 1;
    }

    get_users_worlds($conn, $voter_name, $world_version, $user_name, $page, 15);
}

echo "invalid-name";
exit();