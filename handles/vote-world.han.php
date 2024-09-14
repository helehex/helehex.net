<?php

if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["world_user"]) && isset($_POST["world_name"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $world_user = $_POST["world_user"];
    $world_name = $_POST["world_name"];
} else {
    echo "empty";
    exit();
}

if (isset($_POST["world_vote"])) {
    $world_vote = $_POST["world_vote"];
} else {
    $world_vote = 0;
}

require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

$verified_user = login_user($conn, $username, $password);

if ($verified_user === false) {
    echo "wrong";
    exit();
}

vote_world($conn, $verified_user, $world_user, $world_name, $world_vote);
echo "success";