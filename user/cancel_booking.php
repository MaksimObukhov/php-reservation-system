<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../includes/db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Get the schedule ID of the booking
        $stmt = $pdo->prepare('SELECT schedule_id 
                               FROM bookings 
                               WHERE id = :booking_id AND user_id = :user_id LIMIT 1');
        $stmt->execute([
            ':booking_id' => $booking_id,
            ':user_id' => $user_id
        ]);
        $schedule_id = $stmt->fetchColumn();

        if ($schedule_id) {
            // Delete the booking
            $stmt = $pdo->prepare('DELETE FROM bookings 
                                   WHERE id = :booking_id AND user_id = :user_id');
            $stmt->execute([
                ':booking_id' => $booking_id,
                ':user_id' => $user_id
            ]);

            // Mark the schedule as available
            $stmt = $pdo->prepare('UPDATE schedules SET is_available = TRUE 
                                   WHERE id = :schedule_id');
            $stmt->execute([':schedule_id' => $schedule_id]);

            // Commit transaction
            $pdo->commit();

            // Redirect to profile page
            header('Location: profile.php');
            exit;
        } else {
            // Rollback transaction if schedule ID is not found
            $pdo->rollBack();
            echo 'Booking not found or you do not have permission to cancel this booking.';
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        die('Error cancelling booking: ' . $e->getMessage());
    }
} else {
    header('Location: profile.php');
    exit;
}
