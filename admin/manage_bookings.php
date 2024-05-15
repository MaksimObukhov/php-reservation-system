<?php
include '../includes/admin_header.php';
require_once '../includes/db.php';

// Fetch all bookings
try {
    $bookings_stmt = $pdo->query('SELECT bookings.id, bookings.name, bookings.email, bookings.phone, 
                                  schedules.date, schedules.time, barbers.name AS barber_name 
                                  FROM bookings 
                                  JOIN schedules ON bookings.schedule_id = schedules.id 
                                  JOIN barbers ON schedules.barber_id = barbers.id 
                                  ORDER BY schedules.date, schedules.time');
    $bookings = $bookings_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching bookings: ' . $e->getMessage());
}

// Handle delete request
if (!empty($_POST['delete_booking_id'])) {
    $booking_id = $_POST['delete_booking_id'];
    try {
        // Start transaction
        $pdo->beginTransaction();

        // Get schedule ID
        $schedule_stmt = $pdo->prepare('SELECT schedule_id FROM bookings WHERE id = :booking_id');
        $schedule_stmt->execute([':booking_id' => $booking_id]);
        $schedule_id = $schedule_stmt->fetchColumn();

        // Delete booking
        $delete_stmt = $pdo->prepare('DELETE FROM bookings WHERE id = :booking_id');
        $delete_stmt->execute([':booking_id' => $booking_id]);

        // Mark schedule as available
        $update_schedule_stmt = $pdo->prepare('UPDATE schedules SET is_available = 1 WHERE id = :schedule_id');
        $update_schedule_stmt->execute([':schedule_id' => $schedule_id]);

        // Commit transaction
        $pdo->commit();

        header('Location: manage_bookings.php');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die('Error deleting booking: ' . $e->getMessage());
    }
}
?>

<h1>Manage bookings</h1>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Date</th>
        <th>Time</th>
        <th>Barber</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($bookings as $booking): ?>
        <tr>
            <td><?php echo htmlspecialchars($booking['name']); ?></td>
            <td><?php echo htmlspecialchars($booking['email']); ?></td>
            <td><?php echo htmlspecialchars($booking['phone']); ?></td>
            <td><?php echo htmlspecialchars($booking['date']); ?></td>
            <td><?php echo htmlspecialchars($booking['time']); ?></td>
            <td><?php echo htmlspecialchars($booking['barber_name']); ?></td>
            <td>
                <a href="edit_booking.php?id=<?php echo $booking['id']; ?>">Edit</a>
                <form method="post" action="manage_bookings.php" style="display:inline;">
                    <input type="hidden" name="delete_booking_id" value="<?php echo $booking['id']; ?>">
                    <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this booking?');">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/footer.php';
?>
