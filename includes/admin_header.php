<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Barbershop Booking</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header>
    <h1>Admin dashboard</h1>
    <nav>
        <ul>
            <li><a href="manage_bookings.php">Manage bookings</a></li>
            <li><a href="add_schedule.php">Add schedule</a></li>
            <li><a href="../user/signout.php">Sign out</a></li>
        </ul>
    </nav>
</header>
<main>
