<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
try {
    $user_stmt = $pdo->prepare('SELECT name, email, phone FROM users WHERE id = :user_id LIMIT 1');
    $user_stmt-execute([':user_id' => $user_id]);
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch user bookings
    $bookings_stmt = $pdo->prepare('SELECT b.id, s.date, s.time, ba.name AS barber_name 
                                    FROM bookings b 
                                    JOIN schedules s ON b.schedule_id = s.id 
                                    JOIN barbers ba ON s.barber_id = ba.id 
                                    WHERE b.user_id = :$user_id 
                                    ORDER BY s.date, s.time');
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
                <form method="post" action="cancel_booking.php" style="display:inline;">
                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                    <input type="submit" value="Cancel">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>You have no bookings.</p>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
