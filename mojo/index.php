<?php
session_start();
$gd = filter_var($_GET['gd'], FILTER_VALIDATE_BOOLEAN);
$sm = filter_var($_GET['sm'], FILTER_VALIDATE_BOOLEAN);
$all = empty($_GET);
include_once '../layout/header.php';
?>

<span class="ends">x🔥x</span>

<?php
if ($sm || $all) {
?>

<div class="card">
    <a href="https://docs.google.com/document/d/1VKLr0-RXpbuuYd-iW4_u5zGyO990pd9Gr8bYd84VV-o/edit?usp=sharing">
        <h1>🔥 Mojo Standard Library</h1>
    </a>
    <section>
        <p>Public meetings every sunday at 5pm UTC (12pm CDT), in the modular discord <a href="https://discord.gg/QgM6vmFJ5j">voice channel</a></p>
        <p>Notes from previous meetings can be found <a href="https://docs.google.com/document/d/1VKLr0-RXpbuuYd-iW4_u5zGyO990pd9Gr8bYd84VV-o/edit?usp=sharing">here</a></p>
    </section>
</div>

<?php
}
if ($gd || $all) {
?>

<div class="card">
    <a href="https://discord.gg/sA9WmAVFt5">
        <h1><img src="mojot.svg">Mojo Game Dev</h1>
    </a>
    <section>
        <p>We have a discord server for people interested in game dev with mojo!</p>
        <a class='button mr8 mb8' href="https://discord.gg/sA9WmAVFt5">Join</a><p></p>
    </section>
</div>

<?php
}
?>

<?php include 'layout/footer.php'; ?>