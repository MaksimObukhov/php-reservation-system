<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';

if (!isset($_SESSION['booking_data'])) {
    echo "<p>Invalid input. Please go back and try again.</p>";
    include 'includes/footer.php';
    exit;
}

$booking_data = $_SESSION['booking_data'];
unset($_SESSION['booking_data']);

$name = $booking_data['name'];
$email = $booking_data['email'];
$phone = $booking_data['phone'];
$schedule_id = $booking_data['schedule_id'];

try {
    // Start transaction
    $pdo->beginTransaction();

    // Check if user is logged in
    $user_id = $_SESSION['user_id'] ?? null;

    // If not logged in, check if the email is already registered
    if (!$user_id) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $user_id = $existingUser['id'];
        }
    }

    $booking_stmt = $pdo->prepare('INSERT INTO bookings (name, email, phone, schedule_id, user_id) 
            VALUES (:name, :email, :phone, :schedule_id, :user_id)');
    $booking_stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':schedule_id' => $schedule_id,
        ':user_id' => $user_id !== null ? $user_id : null
    ]);

    // Get schedules
    $schedule_stmt = $pdo->prepare('SELECT schedules.date, schedules.time, barbers.contact, barbers.name AS barber_name
                                    FROM schedules
                                    JOIN barbers ON schedules.barber_id = barbers.id 
                                    WHERE schedules.id = :schedule_id'
    );
    $schedule_stmt->execute([':schedule_id' => $schedule_id]);
    $schedule = $schedule_stmt->fetch(PDO::FETCH_ASSOC);

    // Update the reserved time slot to mark it as unavailable
    $update_schedule_stmt = $pdo->prepare('UPDATE schedules SET is_available = 0 WHERE id = :schedule_id');
    $update_schedule_stmt->execute([':schedule_id' => $schedule_id]);

    $pdo->commit();

    send_confirmation_email(
        $email,
        $name,
        $schedule['date'],
        $schedule['time'],
        $schedule['contact']
    );

    echo "<p>Thank you, $name. Your appointment has been booked for {$schedule['date']} at {$schedule['time']} with {$schedule['barber_name']}.</p>";
} catch (Exception $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    echo "<p>There was an error processing your booking: " . $e->getMessage() . "</p>";
}

include 'includes/footer.php';
