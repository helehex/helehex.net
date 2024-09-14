<?php

session_start();
require_once '../includes/dbh.inc.php';
require_once '../includes/functions.inc.php';

$news = get_news($conn, "new", "point simulator", 60, 0, 1);

include_once 'layout/header.php';

?>

<span class="ends">oOo</span>
<div class="home">
    <a href="https://helehex.net">
        <h1><img src="/images/logo.svg">Made by Helehex</h1>
    </a>
</div>
<div class="card">
    <a href="/pointsimulator/downloads.php">
        <h1>Download</h1>
    </a>
    <section>
        <a href="/pointsimulator/downloads.php">
            <p>( Click for more versions )</p>
        </a>
        <?php echo_latest_pointsim($conn); ?>
    </section>
</div>
<div class="card">
    <a href="#">
        <h1>Learn</h1>
    </a>
    <section>
        <h2>The best place to learn about Point Simulator is the <a
                href="https://discordapp.com/channels/1094023293847224411/1094056645774028820">Helehex Discord</a></h2>
        <p>Video tutorials by Phantospark, more on discord</p>
    </section>
    <section>
        <video class="media-left" controls>
            <source src="https://helehex.net/pointsimulator/videos/gameplay/layer-system.mp4" type="video/mp4">
        </video>
        <video class="media-right" controls>
            <source src="https://helehex.net/pointsimulator/videos/gameplay/snap-points.mp4" type="video/mp4">
        </video>
        <video class="media-left" controls>
            <source src="https://helehex.net/pointsimulator/videos/gameplay/static-collision.mp4" type="video/mp4">
        </video>
        <video class="media-right" controls>
            <source src="https://helehex.net/pointsimulator/videos/gameplay/dynamic-collision.mp4" type="video/mp4">
        </video>
        <video class="media-left" controls>
            <source src="https://helehex.net/pointsimulator/videos/gameplay/rocket-man.mp4" type="video/mp4">
        </video>
        <video class="media-right" controls>
            <source src="https://helehex.net/pointsimulator/videos/gameplay/black-hole.mp4" type="video/mp4">
        </video>
        <video class="media-left" controls>
            <source src="https://helehex.net/pointsimulator/videos/gameplay/music.mp4" type="video/mp4">
        </video>
        <video class="media-right" controls>
            <source src="https://helehex.net/pointsimulator/videos/gameplay/graph.mp4" type="video/mp4">
        </video>
    </section>
</div>
<div class="card">
    <a href="/news/index.php?sort=new&room=point%20simulator&rank=80">
        <h1>News</h1>
    </a>
    <section>
        <br>
        <?php echo $news; ?>
    </section>
</div>
<span class="ends">.o.</span>

<?php include_once 'layout/footer.php'; ?>