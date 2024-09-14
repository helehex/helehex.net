<?php

session_start();

if (isset($_SESSION["user_id"])) {
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';
    roll_user_image($conn);
} else {
    header("Location: ../index.php");
    exit();
}