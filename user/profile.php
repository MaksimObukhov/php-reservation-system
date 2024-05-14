<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../includes/db.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
try {
    $user_stmt = $pdo->prepare('SELECT name, email, phone FROM users WHERE id = ? LIMIT 1');
    $user_stmt->execute([$user_id]);
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch user bookings
    $bookings_stmt = $pdo->prepare('SELECT bookings.id, schedules.date, schedules.time, barbers.name AS barber_name 
                                    FROM bookings 
                                    JOIN schedules ON bookings.schedule_id = schedules.id 
                                    JOIN barbers ON schedules.barber_id = barbers.id 
                                    WHERE bookings.user_id = :user_id 
                                    ORDER BY schedules.date, schedules.time');
    $bookings_stmt->execute([':user_id' => $user_id]);
    $bookings = $bookings_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching user details or bookings: ' . $e->getMessage());
}
?>

<h2>Profile</h2>
<p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
<p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>

<h3>My bookings</h3>
<?php if (!empty($bookings)): ?>
    <ul>
        <?php foreach ($bookings as $booking): ?>
            <li>
                <strong>Date:</strong> <?php echo htmlspecialchars($booking['date']); ?>,
                <strong>Time:</strong> <?php echo htmlspecialchars($booking['time']); ?>,
                <strong>Barber:</strong> <?php echo htmlspecialchars($booking['barber_name']); ?>
                <?php
                $appointment_datetime = new DateTime($booking['date'] . ' ' . $booking['time']);
                $current_datetime = new DateTime();
                if ($appointment_datetime > $current_datetime): ?>
                    <form method="post" action="cancel_booking.php" style="display:inline;">
                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                        <input type="submit" value="Cancel">
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No bookings yet.</p>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
