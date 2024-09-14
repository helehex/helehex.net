<?php

if (
    !isset($_POST["username"])
    || !isset($_POST["password"])
    || !isset($_POST["world_name"])
    || !isset($_POST["world_version"])
    || !isset($_FILES["world_image"])
    || !isset($_FILES["world_data"])
) {
    echo "empty";
    exit();
}

$username = $_POST["username"];
$password = $_POST["password"];
$world_name = $_POST["world_name"];
$world_version = $_POST["world_version"];

if ($_FILES["world_image"]["error"] !== 0 || $_FILES["world_data"]["error"] !== 0) {
    echo "upload-error";
    exit();
}

$world_image = $_FILES["world_image"];
$world_data = $_FILES["world_data"];

require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

$verified_user = login_user($conn, $username, $password);

if ($verified_user === false) {
    echo "wrong";
    exit();
}

$world_count = count_users_worlds($conn, $user);

if ($world_count > 100) {
    echo "too-many-worlds";
    exit();
}

if (invalid_world_name($world_name)) {
    echo "invalid-name";
    exit();
}

if ($world_image["size"] > 50000) {
    echo "image-too-large";
    exit();
}

if ($world_data["size"] > 1000000) {
    echo "world-too-large";
    exit();
}

upload_world($conn, $verified_user, $world_name, $world_version, $world_image, $world_data);
echo "success";