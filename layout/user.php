<?php
if (isset($_SESSION["user_id"])) {
    ?>
    <button class="user">
        <img src=<?php echo "/images/profile/" . $_SESSION["user_image"]; ?>>
        <div>
            <span><?php echo htmlspecialchars($_SESSION["user_name"]); ?></span>
            <hr>
            <a class="center-button" href="/profile.php">Profile</a>
            <a class="center-button" href="https://www.paypal.com/donate/?hosted_button_id=XMY8UHBGC9PAQ">Donate</a>
            <a class="center-button" href="/includes/logout.inc.php">Logout</a>
        </div>
    </button>
    <?php
} else {
    ?>
    <button class="user">
        <img src="/images/profile/guest.svg">
        <div>
            <span>Guest</span>
            <hr>
            <a class="center-button" href="/login.php">Login</a>
            <a class="center-button" href="/register.php">Register</a>
            <a class="center-button" href="https://www.paypal.com/donate/?hosted_button_id=XMY8UHBGC9PAQ">Donate</a>
        </div>
    </button>
    <?php
}
?>