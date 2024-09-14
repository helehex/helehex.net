<?php

if (!isset($_POST["user_name"]) || !isset($_POST["world_name"])) {
    echo "empty";
    exit();
}

$user_name = $_POST["user_name"];
$world_name = $_POST["world_name"];

require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

download_world($conn, $user_name, $world_name);