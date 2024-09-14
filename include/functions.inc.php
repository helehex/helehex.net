<?php

// user login and register

function invalid_username($username)
{
    $len = strlen($username);

    if ($len < 3 || $len > 20 || !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        return true;
    } else {
        return false;
    }
}

function invalid_password($password)
{
    $len = strlen($password);

    if ($len < 3 || $len > 20) {
        return true;
    } else {
        return false;
    }
}

function password_mismatch($password, $confirm_password)
{
    if ($password !== $confirm_password) {
        return true;
    } else {
        return false;
    }
}

function get_user_by_name($conn, $user_name)
{
    $sql = "SELECT * FROM users WHERE user_name = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../error.php?error=connection");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $user_name);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);
    $result = false;

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function get_user_by_id($conn, $user_id)
{
    $sql = "SELECT * FROM users WHERE user_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../error.php?error=connection");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);
    $result = false;

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function create_user($conn, $username, $password)
{
    $sql = "INSERT INTO users (user_name, user_password, user_image, user_rank) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../error.php?error=connection");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $user_image = random_profile_image();
    $user_rank = 0;

    mysqli_stmt_bind_param($stmt, "sssi", $username, $hashed_password, $user_image, $user_rank);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: ../login.php?error=none");
    exit();
}

function login_user($conn, $username, $password)
{
    $user = get_user_by_name($conn, $username);

    if ($user === false) {
        return false;
    }

    $hashed_password = $user["user_password"];
    $check_password = password_verify($password, $hashed_password);

    if ($check_password === true) {
        return $user;
    } else {
        return false;
    }
}



// user image

function random_profile_image()
{
    $files = array_diff(scandir($_SERVER["DOCUMENT_ROOT"] . "/images/profile/default"), array(".", ".."));
    $index = array_rand($files);
    $file = "default/" . $files[$index];
    return $file;
}

function roll_user_image($conn)
{
    $sql = "UPDATE users SET user_image = ? WHERE user_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=f");
        exit();
    }

    $user_id = $_SESSION["user_id"];
    $user_image = random_profile_image();
    $_SESSION["user_image"] = $user_image;

    mysqli_stmt_bind_param($stmt, "si", $user_image, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: ../profile.php");
    exit();
}



// user bios

function get_user_bio($conn, $user_id)
{
    $current_user = isset($_SESSION["user_id"]) && ($_SESSION["user_id"] === $user_id);

    if ($current_user && isset($_SESSION["user_bio"])) {
        return $_SESSION["user_bio"];
    } else {
        $sql = "SELECT user_bio FROM bios WHERE user_id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../error.php?error=connection");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        $result_data = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result_data)) {
            $result = $row["user_bio"];

            if ($current_user) {
                $_SESSION["user-bio"] = $result;
            }
        } else {
            $result = "none";
        }

        mysqli_stmt_close($stmt);
        return $result;
    }
}

function set_user_bio($conn, $user_bio)
{
    $sql = "REPLACE INTO bios (user_id, user_bio) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../error.php?error=connection");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "is", $_SESSION["user_id"], $user_bio);
    mysqli_stmt_execute($stmt);
    $_SESSION["user_bio"] = $user_bio;
    mysqli_stmt_close($stmt);
    header("Location: ../profile.php");
    exit();
}



// helehex news

function get_news($conn, $sort, $room, $rank, $page, $count)
{
    $start = $page * $count;

    if ($sort === "old") {
        $sql = "SELECT * FROM news WHERE news_room = ? AND user_rank >= ? ORDER BY news_id ASC LIMIT ?, ?;";
    } else {
        $sql = "SELECT * FROM news WHERE news_room = ? AND user_rank >= ? ORDER BY news_id DESC LIMIT ?, ?;";
    }

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../error.php?error=connection");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "siii", $room, $rank, $start, $count);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);
    $result = "";
    $num_rows = mysqli_num_rows($result_data);

    $return_to = "/news/index.php?sort=" . $sort . "&room=" . $room . "&rank=" . $rank . "&page=" . $page;

    if ($num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result_data)) {
            $result .= news_to_html($conn, $row, $return_to);
        }
    } else {
        $result .= "<h2>no articles</h2>";
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function get_news_by_id($conn, $news_id)
{
    $sql = "SELECT * FROM news WHERE news_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../error.php?error=connection");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $news_id);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);
    $result = false;

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function news_to_html($conn, $news, $return_to)
{
    $result = "";
    $user = get_user_by_id($conn, $news["user_id"]);
    $result .= "<article><div class='top'><div class='left selectable'><h4>";
    $result .= $user["user_name"] . "</h4><h3>" . $news["news_title"];
    $result .= "</h3><hr class='clear'></div><div class='right'><a class='user' href='/profile.php?user=";
    $result .= $user["user_name"] . "'><img src='/images/profile/" . $user["user_image"] . "'></a></div></div><p class='selectable'>";
    $result .= $news["news_body"] . "</p><div class='bottom'><time>" . date_format(date_create($news["news_date"]), "M - d - Y") . "</time>";

    if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] === $news["user_id"]) {
        $result .= "<a class='right-button mb20' href='/news/includes/remove-news.inc.php?back=" . urlencode($return_to) . "&id=" . $news["news_id"] . "'>Remove</a>";
    }

    $result .= "</div></article>";
    return $result;
}

function post_news($conn, $user_id, $rank, $room, $title, $body)
{
    $sql = "INSERT INTO news (user_id, user_rank, news_room, news_title, news_body) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../error.php?error=connection");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "iisss", $_SESSION["user_id"], $rank, $room, $title, $body);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function news_count($conn)
{
    $result = mysqli_query($conn, "SELECT MAX(news_id) FROM news;");
    $row = mysqli_fetch_array($result);
    return $row[0];
}

function remove_news($conn, $news_id)
{
    $sql = "DELETE FROM news WHERE news_id = ? AND user_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../error.php?error=connection");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $news_id, $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// point simulator version

function get_latest_pointsim($conn)
{
    $sql = "SELECT * FROM pointsim_version ORDER BY news_id DESC LIMIT 1;";
    $result_data = mysqli_query($conn, $sql);
    $result = false;

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row;
    }

    return $result;
}

function echo_latest_pointsim($conn)
{
    $latest_pointsim = get_latest_pointsim($conn);

    if ($latest_pointsim === false) {
        echo "<h2>No Versions</h2>";
    } else {
        echo_pointsim_version($latest_pointsim["version_name"]);
    }
}

function get_all_pointsim_versions($conn)
{
    $sql = "SELECT * FROM pointsim_version ORDER BY news_id DESC;";
    $result_data = mysqli_query($conn, $sql);
    $num_rows = mysqli_num_rows($result_data);

    if ($num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result_data)) {
            echo news_to_html($conn, get_news_by_id($conn, $row["news_id"]), "/pointsimulator/downloads.php");
            echo echo_pointsim_version($row["version_name"]);
            echo "<hr class='clear'>";
        }
    } else {
        echo "<h2>No Versions</h2>";
    }
}

function echo_pointsim_version($version)
{
    $dir = "/pointsimulator/versions/" . $version;
    $windows_dir = $dir . "/windows/" . $version . ".zip";
    $mac_dir = $dir . "/mac/" . $version . ".zip";
    $linux_dir = $dir . "/linux/" . $version . ".zip";
    $name = "Point Simulator " . $version;

    echo "<div class='downloads'>";

    if (file_exists($_SERVER["DOCUMENT_ROOT"] . $windows_dir)) {
        echo "<a class='button mr8 mb8' href='" . $windows_dir . "' download='" . $name . "'>Windows</a>";
    } else {
        echo "<a class='button mr8 mb8 inactive' href='#' onClick='return false;'>Windows</a>";
    }

    if (file_exists($_SERVER["DOCUMENT_ROOT"] . $mac_dir)) {
        echo "<a class='button mr8 mb8' href='" . $mac_dir . "' download='" . $name . "'>Mac</a>";
    } else {
        echo "<a class='button mr8 mb8 inactive' href='#' onClick='return false;'>Mac</a>";
    }

    if (file_exists($_SERVER["DOCUMENT_ROOT"] . $linux_dir)) {
        echo "<a class='button mr8 mb8' href='" . $linux_dir . "' download='" . $name . "'>Linux</a>";
    } else {
        echo "<a class='button mr8 mb8 inactive' href='#' onClick='return false;'>Linux</a>";
    }

    echo "<span>- " . $version . "</span></div>";
}

function get_pointsim_version($conn, $version_name)
{
    $sql = "SELECT * FROM pointsim_version WHERE version_name = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../error.php?error=connection");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $version_name);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);
    $result = false;

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function upload_pointsim($conn, $version, $title, $changes, $windows, $mac, $linux)
{
    $existing_version = get_pointsim_version($conn, $version);

    if ($existing_version === false) {
        $existing_news = false;
    } else {
        $existing_news = get_news_by_id($conn, $existing_version["news_id"]);
    }

    if ($existing_news === false) {
        $sql = "INSERT INTO news (user_id, user_rank, news_room, news_title, news_body) VALUES (?, 85, 'point simulator', ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../error.php?error=connection");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "iss", $_SESSION["user_id"], $title, $changes);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $news_id = mysqli_insert_id($conn);
    } else {
        $news_id = $existing_news["news_id"];
        $sql = "REPLACE INTO news (news_id, user_id, user_rank, news_room, news_title, news_body) VALUES (?, ?, 85, 'point simulator', ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../error.php?error=connection");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "iiss", $news_id, $_SESSION["user_id"], $title, $changes);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    $sql = "REPLACE INTO pointsim_version (version_name, news_id) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../error.php?error=connection");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "si", $version, $news_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $dir = "../versions/" . $version;

    if ($windows !== false) {
        $windows_dir = $dir . "/windows/";
        mkdir($windows_dir, 0777, true);
        move_uploaded_file($windows["tmp_name"], $windows_dir . $version . ".zip");
    }

    if ($mac !== false) {
        $mac_dir = $dir . "/mac/";
        mkdir($mac_dir, 0777, true);
        move_uploaded_file($mac["tmp_name"], $mac_dir . $version . ".zip");
    }

    if ($linux !== false) {
        $linux_dir = $dir . "/linux/";
        mkdir($linux_dir, 0777, true);
        move_uploaded_file($linux["tmp_name"], $linux_dir . $version . ".zip");
    }
}

// point simulator worlds

function invalid_world_name($world_name)
{
    $len = strlen($world_name);

    if ($len < 1 || $len > 20 || !preg_match("/^[a-z0-9 .\-]+$/i", $world_name)) {
        return true;
    } else {
        return false;
    }
}

function search_worlds($conn, $voter_name, $world_version, $search, $sort, $page, $limit)
{
    $voter_id = false;

    if (!empty($voter_name)) {
        $voter_user = get_user_by_name($conn, $voter_name);

        if ($voter_user !== false) {
            $voter_id = $voter_user["user_id"];
        }
    }

    $search = "%" . $search . "%";
    $world_version .= "%";

    if ($page < 0) {
        $start = (-1 - $page) * $limit;
        $sql_order_sign = "ASC";
    } else {
        $start = ($page - 1) * $limit;
        $sql_order_sign = "DESC";
    }

    if ($sort === "top") {
        $sql_order = "ORDER BY (upvotes - downvotes) " . $sql_order_sign;
    } else if ($sort === "war") {
        $sql_order = "ORDER BY ((upvotes + downvotes) / (ABS(upvotes - downvotes) + 0.01)) " . $sql_order_sign;
    } else {
        $sql_order = "ORDER BY w.world_id " . $sql_order_sign;
    }

    $sql =
        "SELECT *,
    (SELECT COUNT(*) FROM world_rating r WHERE r.world_id = w.world_id AND r.world_vote > 0) AS upvotes,
    (SELECT COUNT(*) FROM world_rating r WHERE r.world_id = w.world_id AND r.world_vote < 0) AS downvotes
    FROM worlds w WHERE w.world_name LIKE ? AND w.world_version LIKE ? " . $sql_order . " LIMIT ?, ?;";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        exit("failed");
    }

    mysqli_stmt_bind_param($stmt, "ssii", $search, $world_version, $start, $limit);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);
    $num_rows = mysqli_num_rows($result_data);

    if ($num_rows > 0) {
        $worlds = array();

        while ($row = mysqli_fetch_assoc($result_data)) {
            $user = get_user_by_id($conn, $row["user_id"]);

            if ($user === false) {
                exit("invalid-user");
            }

            $user_name = $user["user_name"];
            $world_image = base64_encode(file_get_contents("../pointsimulator/worlds/" . $row["user_id"] . "/" . $row["world_id"] . "/image.png"));
            $world_vote = get_user_world_vote($conn, $voter_id, $row["world_id"]);

            $worlds[] =
                array(
                    "user_name" => $user_name,
                    "world_name" => $row["world_name"],
                    "world_version" => $row["world_version"],
                    "world_image" => $world_image,
                    "world_upvotes" => $row["upvotes"],
                    "world_downvotes" => $row["downvotes"],
                    "world_vote" => $world_vote
                );
        }
    } else {
        exit("no-worlds");
    }

    mysqli_stmt_close($stmt);
    header('Content-Type: application/json');
    echo json_encode(["worlds" => $worlds]);
    exit;
}

function search_users($conn, $search, $page, $limit)
{
    $search = "%" . $search . "%";

    if ($page < 0) {
        $start = (-1 - $page) * $limit;
        $sql = "SELECT * FROM users WHERE user_name LIKE ? ORDER BY user_rank ASC LIMIT ?, ?;";
    } else {
        $start = ($page - 1) * $limit;
        $sql = "SELECT * FROM users WHERE user_name LIKE ? ORDER BY user_rank DESC LIMIT ?, ?;";
    }

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        exit("failed");
    }

    mysqli_stmt_bind_param($stmt, "sii", $search, $start, $limit);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);
    $num_rows = mysqli_num_rows($result_data);
    $users = "users";

    if ($num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result_data)) {
            $users .= "," . $row["user_name"];
        }
    }

    mysqli_stmt_close($stmt);
    echo $users;
    exit;
}

function get_users_worlds($conn, $voter_name, $world_version, $user_name, $page, $limit)
{
    $voter_id = false;

    if (!empty($voter_name)) {
        $voter_user = get_user_by_name($conn, $voter_name);

        if ($voter_user !== false) {
            $voter_id = $voter_user["user_id"];
        }
    }

    $user = get_user_by_name($conn, $user_name);

    if ($user === false) {
        exit("invalid-user");
    }

    if ($page < 1) {
        $page = 1;
    }

    $start = ($page - 1) * $limit;

    $sql =
        "SELECT *,
    (SELECT COUNT(*) FROM world_rating r WHERE r.world_id = w.world_id AND r.world_vote > 0) AS upvotes,
    (SELECT COUNT(*) FROM world_rating r WHERE r.world_id = w.world_id AND r.world_vote < 0) AS downvotes
    FROM worlds w WHERE w.user_id = ? AND w.world_version LIKE ? ORDER BY w.world_id DESC LIMIT ?, ?;";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        exit("failed");
    }

    mysqli_stmt_bind_param($stmt, "isii", $user["user_id"], $world_version, $start, $limit);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);
    $num_rows = mysqli_num_rows($result_data);

    if ($num_rows > 0) {
        $worlds = array();

        while ($row = mysqli_fetch_assoc($result_data)) {
            $world_image = base64_encode(file_get_contents("../pointsimulator/worlds/" . $row["user_id"] . "/" . $row["world_id"] . "/image.png"));
            $world_vote = get_user_world_vote($conn, $voter_id, $row["world_id"]);
            $worlds[] =
                array(
                    "user_name" => $user_name,
                    "world_name" => $row["world_name"],
                    "world_version" => $row["world_version"],
                    "world_image" => $world_image,
                    "world_upvotes" => $row["upvotes"],
                    "world_downvotes" => $row["downvotes"],
                    "world_vote" => $world_vote
                );
        }
    } else {
        exit("no-worlds");
    }

    mysqli_stmt_close($stmt);
    header('Content-Type: application/json');
    echo json_encode(["worlds" => $worlds]);
    exit;
}

function download_world($conn, $user_name, $world_name)
{
    $user = get_user_by_name($conn, $user_name);

    if ($user === false) {
        echo "invalid-user";
        exit();
    }

    $world = get_world_by_uiwn($conn, $user["user_id"], $world_name);

    if ($world === false) {
        echo "missing-world";
        exit();
    }

    $file = "../pointsimulator/worlds/" . $user["user_id"] . "/" . $world["world_id"] . "/data.dat";
    header("Content-Type: application/octet-stream");
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit();
}

function get_world_by_unwn($conn, $user_name, $world_name)
{
    $user = get_user_by_name($conn, $user_name);

    if ($user === false) {
        exit("invalid-user");
    }

    return get_world_by_uiwn($conn, $user["user_id"], $world_name);
}

function get_world_by_uiwn($conn, $user_id, $world_name)
{
    $sql = "SELECT * FROM worlds WHERE user_id = ? AND world_name = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        exit("failed");
    }

    mysqli_stmt_bind_param($stmt, "is", $user_id, $world_name);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row;
    } else {
        $result = false;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function count_users_worlds($conn, $user)
{
    $sql = "SELECT COUNT(*) AS count FROM worlds WHERE user_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "failed";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $user["user_id"]);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row["count"];
    } else {
        $result = 0;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function upload_world($conn, $user, $world_name, $world_version, $world_image, $world_data)
{
    $existing_world = get_world_by_uiwn($conn, $user["user_id"], $world_name);

    if ($existing_world === false) {
        $sql = "INSERT INTO worlds (user_id, world_name, world_version) VALUES (?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            exit("failed");
        }
        mysqli_stmt_bind_param($stmt, "iss", $user["user_id"], $world_name, $world_version);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $world_id = mysqli_insert_id($conn);
    } else {
        $world_id = $existing_world["world_id"];
        $sql = "REPLACE INTO worlds (world_id, user_id, world_name, world_version) VALUES (?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            exit("failed");
        }
        mysqli_stmt_bind_param($stmt, "iiss", $world_id, $user["user_id"], $world_name, $world_version);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    $world_dir = "../pointsimulator/worlds/" . $user["user_id"] . "/" . $world_id;
    mkdir($world_dir, 0777, true);

    $world_image_tmp = $world_image["tmp_name"];
    $world_image_path = $world_dir . "/image.png";
    move_uploaded_file($world_image_tmp, $world_image_path);

    $world_data_tmp = $world_data["tmp_name"];
    $world_data_path = $world_dir . "/data.dat";
    move_uploaded_file($world_data_tmp, $world_data_path);
}

function delete_world($conn, $user, $world_name)
{
    $existing_world = get_world_by_uiwn($conn, $user["user_id"], $world_name);

    if ($existing_world === false) {
        echo "missing-world";
        exit;
    }

    $sql = "DELETE FROM worlds WHERE user_id = ? AND world_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        exit("failed");
    }

    mysqli_stmt_bind_param($stmt, "ii", $user["user_id"], $existing_world["world_id"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    rmdir("../pointsimulator/worlds/" . $user["user_id"] . "/" . $existing_world["world_id"]);
}

function count_world_upvotes($conn, $world_id)
{
    $sql = "SELECT COUNT(*) AS count FROM world_rating WHERE world_id = ? AND world_vote = 1;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "failed";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $world_id);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row["count"];
    } else {
        $result = 0;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function count_world_downvotes($conn, $world_id)
{
    $sql = "SELECT COUNT(*) AS count FROM world_rating WHERE world_id = ? AND world_vote = -1;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "failed";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $world_id);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row["count"];
    } else {
        $result = 0;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function count_world_votes($conn, $world_id)
{
    $sql = "SELECT COUNT(*) AS count FROM world_rating WHERE world_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "failed";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $world_id);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row["count"];
    } else {
        $result = 0;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function sum_world_votes($conn, $world_id)
{
    $sql = "SELECT SUM(world_vote) AS sum FROM world_rating WHERE world_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "failed";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $world_id);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row["sum"];
    } else {
        $result = 0;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function get_user_world_vote($conn, $user_id, $world_id)
{
    if ($user_id === false) {
        return 0;
    }

    $sql = "SELECT world_vote FROM world_rating WHERE user_id = ? AND world_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "failed";
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $user_id, $world_id);
    mysqli_stmt_execute($stmt);

    $result_data = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result_data)) {
        $result = $row["world_vote"];
    } else {
        $result = 0;
    }

    mysqli_stmt_close($stmt);
    return $result;
}

function vote_world($conn, $user, $world_user, $world_name, $vote)
{
    $existing_world = get_world_by_unwn($conn, $world_user, $world_name);

    if ($existing_world === false) {
        echo "missing-world";
        exit;
    }

    if ($vote > 1) {
        $vote = 1;
    } else if ($vote < -1) {
        $vote = -1;
    }

    if ($vote === 0) {
        $sql = "DELETE FROM world_rating WHERE world_id = ? AND user_id = ?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            exit("failed");
        }

        mysqli_stmt_bind_param($stmt, "ii", $user["user_id"], $existing_world["world_id"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $sql = "REPLACE INTO world_rating (world_id, user_id, world_vote) VALUES (?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            exit("failed");
        }

        mysqli_stmt_bind_param($stmt, "iii", $existing_world["world_id"], $user["user_id"], $vote);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
