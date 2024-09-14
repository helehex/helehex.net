<?php

session_start();
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';

$news = get_news($conn, "new", "helehex", 60, 0, 1);

include_once 'layout/header.php';

?>

<span class="ends">xHx</span>
<div class="card">
    <a href="/pointsimulator">
        <h1><img src="/pointsimulator/images/logo.svg">Point Simulator</h1>
    </a>
    <section>
        <a href="/pointsimulator">
            <img src="/pointsimulator/images/gameplay/splash_0.png" class="media-left">
            <h2>Enter the universe of point simulation <span class="free">FREE!</span></h2>
            <ul>
                <li>Create games using points and physics</li>
                <li>Play worlds uploaded by other people</li>
                <li>Link worlds together for larger projects</li>
                <li>Local multiplayer with controller support</li>
            </ul>
        </a>
        <?php echo_latest_pointsim($conn); ?>
    </section>
    <section>
        <video class="media-left" controls>
            <source src="https://helehex.net/pointsimulator/videos/gameplay/hub.mp4" type="video/mp4">
        </video>
        <video class="media-right" controls>
            <source src="https://helehex.net/pointsimulator/videos/gameplay/snap-points.mp4" type="video/mp4">
        </video>
    </section>
</div>
<div class="card">
    <a href="#">
        <h1><img src="/pocketfactory/images/logo.svg">Pocket Factory</h1>
    </a>
    <section>
        <a href="/pocketfactory">
            <img src="/pocketfactory/images/gameplay/capture.png" class="media-left">
            <h2>Score big by building machines that solve puzzles <span class="free">FREE!</span></h2>
            <ul>
                <li>Start out with nothing but void</li>
                <li>Click your way to cookies, and much more</li>
                <li>Build machines designed to solve puzzles</li>
                <li>Score huge numbers along the way</li>
                <li>Coming eventually</li>
            </ul>
        </a>
    </section>
</div>
<div class="card">
    <a href="/news/index.php?sort=new&room=helehex&rank=60">
        <h1>News</h1>
    </a>
    <section>
        <br>
        <?php echo $news; ?>
    </section>
    <a href="/news/index.php?sort=new&room=helehex&rank=60">
        <section>
            <h2>Click to see more articles</h2>
        </section>
    </a>
</div>
<div class="card">
    <a href="/about.php">
        <h1>About</h1>
        <section>
            <h2>Dedicated to the sandbox, click to learn more</h2>
        </section>
    </a>
</div>
<span class="ends">.x.</span>

<?php include 'layout/footer.php'; ?>