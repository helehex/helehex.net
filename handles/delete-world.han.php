<?php

if (!isset($_POST["username"]) || !isset($_POST["password"]) || !isset($_POST["world_name"])) {
    echo "empty";
    exit();
}

$username = $_POST["username"];
$password = $_POST["password"];
$world_name = $_POST["world_name"];

require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

$verified_user = login_user($conn, $username, $password);

if ($verified_user === false) {
    echo "wrong";
    exit();
}

delete_world($conn, $verified_user, $world_name);
echo "success";