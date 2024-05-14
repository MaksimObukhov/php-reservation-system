<?php
require_once 'includes/db.php';
include 'includes/header.php';

// Fetch available schedules
try {
    // Filter out schedules that are in the past and are already booked
    $stmt = $pdo->query('SELECT s.id, b.name AS barber_name, s.date, s.time 
                         FROM schedules s
                         JOIN barbers b ON s.barber_id = b.id 
                         WHERE s.date >= CURDATE() AND s.is_available = TRUE
                         ORDER BY s.date, s.time');
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching schedules: ' . $e->getMessage());
}
?>

<h2>Book an Appointment</h2>
<form action="confirm_booking.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" required><br>

    <label for="schedule">Choose a time slot:</label>
    <select id="schedule" name="schedule_id" required>
    <?php foreach ($schedules as $schedule): ?>
      <option value="<?php echo $schedule['id']; ?>">
        <?php echo "{$schedule['barber_name']} - {$schedule['date']} at {$schedule['time']}"; ?>
      </option>
    <?php endforeach; ?>
    </select><br>

    <button type="submit">Book Appointment</button>
</form>

<?php
include 'includes/footer.php';
?>
