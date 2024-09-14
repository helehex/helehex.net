<?php

session_start();
require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

$sort = "new";
$room = "e";
$rank = 0;
$page = 0;
$count = 4;

if (isset($_GET["sort"])) {
    $sort = trim(strtolower($_GET["sort"]));

    if ($sort !== "new" && $sort !== "old") {
        $sort = "new";
    }
}

if (isset($_GET["room"])) {
    $room = trim(strtolower($_GET["room"]));

    if (empty($room)) {
        $room = "e";
    }
}

if (isset($_GET["rank"])) {
    $rank = $_GET["rank"];
}

if (isset($_GET["page"])) {
    $page = $_GET["page"];

    if ($page < 0) {
        $page = 0;
    }
}

$get = "?sort=" . $sort . "&room=" . $room . "&rank=" . $rank;
$news = get_news($conn, $sort, $room, $rank, $page, $count);

if ($room === "helehex") {
    include_once '../layout/header.php';
} elseif ($room === "point simulator") {
    include_once '../pointsimulator/layout/header.php';
} else {
    include_once 'layout/header.php';
}

?>

<span class="ends">wMw</span>

<?php

if (isset($_GET["error"])) {
    if ($_GET["error"] === "rank") {
        ?>
        <div class="error">
            Not authorized to post here !
        </div>
        <?php
    }

    if ($_GET["error"] === "empty") {
        ?>
        <div class="notice">
            Write an article
        </div>
        <?php
    }

    if ($_GET["error"] === "overflow") {
        ?>
        <div class="error">
            Article is too big !
        </div>
        <?php
    }
}

?>

<div class="card">

    <?php
    echo "<h1>News - " . $room . " - " . $page . "</h1>";

    if (!isset($_GET["write"])) {
        ?>
        <section>
            <form class="search">
                <select class="mb20" name="sort">
                    <option value="new" <?php if ($sort === "new") {
                        echo "selected";
                    } ?>>New</option>
                    <option value="old" <?php if ($sort === "old") {
                        echo "selected";
                    } ?>>Old</option>
                </select>
                <input class="search-bar mb20" type="text" name="room" value=<?php echo "'" . $room . "'"; ?>>
                <input class="button mb20" type="submit" value="Go">
            </form>
            <a class="right-button mb20" href=<?php echo "'/news/index.php" . $get . "&write=on'"; ?>>Write</a>
        </section>
        <?php
    } else {
        ?>
        <section>
            <form class="write-news" action=<?php echo "'/news/includes/post-news.inc.php" . $get . "'"; ?> method="post">
                <a class="button mb20" href=<?php echo "'/news/index.php" . $get . "'"; ?>>Cancel</a>
                <input class="right-button mb20" type="submit" name="submit" value="post">
                <input class="room mb20" type="text" name="title">
                <textarea name="body"></textarea>
            </form>
        </section>
        <?php
    }

    ?>

    <section>
        <br>

        <?php

        echo $news;
        echo "<a class='button mb20' href='/news/index.php" . $get . "&page=" . ($page - 1) . "'>Previous</a>";
        echo "<a class='right-button mb20' href='/news/index.php" . $get . "&page=" . ($page + 1) . "'>More</a>";

        ?>

    </section>
</div>
<span class="ends">.w.</span>

<?php include 'layout/footer.php'; ?>