<?php

$username = $_POST["username"];
$password = $_POST["password"];

require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

if ((empty($username) || empty($password)) !== false) {
    echo "empty";
    exit();
}

$verified_user = login_user($conn, $username, $password);

if ($verified_user === false) {
    echo "wrong";
    exit();
}

echo "success," . $verified_user["user_name"];
exit();