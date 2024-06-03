<?php
include '../includes/admin_header.php';
require_once '../includes/db.php';

$filterDate = $_GET['filter_date'] ?? '';
$filterBarber = $_GET['filter_barber'] ?? '';
$sortField = $_GET['sort_field'] ?? 'schedules.date';
$sortOrder = $_GET['sort_order'] ?? 'ASC';

try {
    $barbers_stmt = $pdo->query('SELECT id, name FROM barbers');
    $barbers = $barbers_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching barbers: ' . $e->getMessage());
}

// Build the query with filters and sorting
$query = 'SELECT bookings.id, bookings.name, bookings.email, bookings.phone, 
                  schedules.date, schedules.time, barbers.name AS barber_name 
          FROM bookings 
          JOIN schedules ON bookings.schedule_id = schedules.id 
          JOIN barbers ON schedules.barber_id = barbers.id ';

$params = [];
if ($filterDate) {
    $query .= 'WHERE schedules.date = :filter_date ';
    $params[':filter_date'] = $filterDate;
}
if ($filterBarber) {
    $query .= ($filterDate ? 'AND' : 'WHERE') . ' schedules.barber_id = :filter_barber ';
    $params[':filter_barber'] = $filterBarber;
}

$query .= "ORDER BY $sortField $sortOrder";

try {
    $bookings_stmt = $pdo->prepare($query);
    $bookings_stmt->execute($params);
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

<form method="GET" action="manage_bookings.php">
    <label for="filter_date">Filter by Date:</label>
    <input type="date" name="filter_date" id="filter_date" value="<?php echo htmlspecialchars($filterDate); ?>">

    <label for="filter_barber">Filter by Barber:</label>
    <select name="filter_barber" id="filter_barber">
        <option value="">Select a barber</option>
        <?php foreach ($barbers as $barber): ?>
            <option value="<?php echo $barber['id']; ?>" <?php echo ($barber['id'] == $filterBarber) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($barber['name']); ?>
            </option>
        <?php endforeach; ?>
    </select><br/><br/>

    <label for="sort_field">Sort by:</label>
    <select name="sort_field" id="sort_field">
        <option value="schedules.date" <?php echo ($sortField == 'schedules.date') ? 'selected' : ''; ?>>Date</option>
        <option value="schedules.time" <?php echo ($sortField == 'schedules.time') ? 'selected' : ''; ?>>Time</option>
        <option value="barbers.name" <?php echo ($sortField == 'barbers.name') ? 'selected' : ''; ?>>Barber</option>
    </select>

    <select name="sort_order" id="sort_order">
        <option value="ASC" <?php echo ($sortOrder == 'ASC') ? 'selected' : ''; ?>>Ascending</option>
        <option value="DESC" <?php echo ($sortOrder == 'DESC') ? 'selected' : ''; ?>>Descending</option>
    </select><br/><br/>

    <input type="submit" value="Apply"><br/><br/>
</form>

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

