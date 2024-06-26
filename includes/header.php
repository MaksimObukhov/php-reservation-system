<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbershop Booking</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/styles.css">
</head>
<body>
<header>
    <h1>Barbershop Booking</h1>
    <nav>
        <ul>
            <li><a href="<?php echo BASE_URL; ?>/index.php">Home</a></li>
            <li><a href="<?php echo BASE_URL; ?>/booking.php">Book appointment</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?php echo BASE_URL; ?>/user/profile.php">Profile</a></li>
                <li><a href="<?php echo BASE_URL; ?>/user/signout.php">Sign out</a></li>
            <?php else: ?>
                <li><a href="<?php echo BASE_URL; ?>/user/login.php">Login</a></li>
                <li><a href="<?php echo BASE_URL; ?>/user/register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
