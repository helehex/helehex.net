<?php

require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

if (isset($_POST["search"])) {
    $search = $_POST["search"];
} else {
    $search = "";
}

if (isset($_POST["page"])) {
    $page = $_POST["page"];
} else {
    $page = 1;
}

search_users($conn, $search, $page, 15);