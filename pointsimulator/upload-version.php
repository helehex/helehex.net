<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_rank"] < 85) {
    header("Location: downloads.php");
    exit();
}

require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

$latest_pointsim = get_latest_pointsim($conn);
$latest_version = "";

if ($latest_pointsim !== false) {
    $latest_version = $latest_pointsim["version_name"];
}

include_once 'layout/header.php';

?>

<span class="ends">oOo</span>
<div class="card">
    <h1>Downloads</h1>
    <section>
        <form class="write-news" action='/pointsimulator/includes/upload-version.inc.php' enctype="multipart/form-data"
            method="post">
            <a class="button mb20" href='/pointsimulator/downloads.php'>Cancel</a>
            <input class="right-button mb20" type="submit" name="submit" value="post">
            <br>
            <input class="button mb20 mr8" type="file" name="windows" value="windows" id="windows">Windows
            <br>
            <input class="button mb20 mr8" type="file" name="mac" value="mac">Mac
            <br>
            <input class="button mb20 mr8" type="file" name="linux" value="linux">Linux
            <input class="room mb20" type="text" name="version" value=<?php echo $latest_version; ?>>
            <input class="room mb20" type="text" name="title">
            <textarea name="changes"></textarea>
        </form>
    </section>
</div>
<span class="ends">.o.</span>

<?php include_once 'layout/footer.php'; ?>