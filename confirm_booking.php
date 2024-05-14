<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';

$errors = [];

if (!empty($_POST)) {
    #region Form submission processing and validation
    $name = trim($_POST['name']);
    if (empty($name)) {
        $errors['name'] = 'Musíte zadat své jméno.';
    }

    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Musíte zadat platnou e-mailovou adresu.';
    }

    $phone = $_POST['phone'];
    if (empty($phone)) {
        $errors['phone'] = 'Musíte zadat své telefonní číslo.';
    }

    $schedule_id = $_POST['schedule_id'];
    if (empty($schedule_id)) {
        $errors['schedule_id'] = 'Musíte si výbrat termín.';
    }
    #endregion Form submission processing and validation

    if (empty($errors)) {
        try {
            // Start transaction
            $pdo->beginTransaction();

            // Insert booking into database
            $booking_stmt = $pdo->prepare('INSERT INTO bookings (name, email, phone, schedule_id) 
                          VALUES (:name, :email, :phone, :schedule_id)');
            $booking_stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':schedule_id' => $schedule_id
            ]);

            // TODO: add filter is_available == 1
            // Get schedule details
            $schedule_stmt = $pdo->prepare('SELECT s.date, s.time, b.name AS barber_name
                                            FROM schedules s
                                            JOIN barbers b ON s.barber_id = b.id 
                                            WHERE s.id = :schedule_id'

            );
            $schedule_stmt->execute([
                ':schedule_id' => $schedule_id
            ]);
            $schedule = $schedule_stmt->fetch(PDO::FETCH_ASSOC);

            // Update the reserved time slot to mark it as unavailable
            $update_schedule_stmt = $pdo->prepare('UPDATE schedules SET is_available = 0 WHERE id = :schedule_id');
            $update_schedule_stmt->execute([
                ':schedule_id' => $schedule_id
            ]);

            // Commit transaction
            $pdo->commit();

            // Send confirmation email
            send_confirmation_email($email, $name, $schedule['date'], $schedule['time']);

            echo "<p>Thank you, $name. Your appointment has been booked for {$schedule['date']} at {$schedule['time']} with {$schedule['barber_name']}.</p>";
        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            echo "<p>There was an error processing your booking: " . $e->getMessage() . "</p>";
        }
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
} else {
    echo "<p>Invalid input. Please go back and try again.</p>";
}

include 'includes/footer.php';
