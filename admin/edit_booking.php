<?php
include '../includes/admin_header.php';
require_once '../includes/db.php';

$booking_id = $_GET['id'];
$formError = '';

// Fetch booking details
try {
    $booking_stmt = $pdo->prepare('SELECT bookings.id, bookings.name, bookings.email, bookings.phone, 
                                   schedules.date, schedules.time, barbers.id AS barber_id, barbers.name AS barber_name 
                                   FROM bookings 
                                   JOIN schedules ON bookings.schedule_id = schedules.id 
                                   JOIN barbers ON schedules.barber_id = barbers.id 
                                   WHERE bookings.id = :booking_id');
    $booking_stmt->execute([':booking_id' => $booking_id]);
    $booking = $booking_stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching booking details: ' . $e->getMessage());
}

// Update booking
if (!empty($_POST)) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $barber_id = $_POST['barber_id'];

    if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($time) || empty($barber_id)) {
        $formError = 'All fields are required.';
    } else {
        try {
            // Start transaction
            $pdo->beginTransaction();

            // Update the booking
            $update_stmt = $pdo->prepare('UPDATE bookings 
                                          JOIN schedules ON bookings.schedule_id = schedules.id 
                                          SET bookings.name = :name, bookings.email = :email, bookings.phone = :phone, 
                                              schedules.date = :date, schedules.time = :time, schedules.barber_id = :barber_id 
                                          WHERE bookings.id = :booking_id');
            $update_stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':date' => $date,
                ':time' => $time,
                ':barber_id' => $barber_id,
                ':booking_id' => $booking_id
            ]);

            // Commit transaction
            $pdo->commit();

            header('Location: manage_bookings.php');
            exit;
        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            $formError = 'Error updating booking: ' . $e->getMessage();
        }
    }
}
?>

<h1>Edit booking</h1>
<?php
if (!empty($formError)) {
    echo '<p style="color:red;">' . $formError . '</p>';
}
?>
<form method="post">
    <label for="name">Name:</label><br/>
    <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($booking['name']); ?>"><br/><br/>

    <label for="email">Email:</label><br/>
    <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($booking['email']); ?>"><br/><br/>

    <label for="phone">Phone:</label><br/>
    <input type="text" name="phone" id="phone" required value="<?php echo htmlspecialchars($booking['phone']); ?>"><br/><br/>

    <label for="date">Date:</label><br/>
    <input type="date" name="date" id="date" required value="<?php echo htmlspecialchars($booking['date']); ?>"><br/><br/>

    <label for="time">Time:</label><br/>
    <input type="time" name="time" id="time" required value="<?php echo htmlspecialchars($booking['time']); ?>"><br/><br/>

    <label for="barber">Barber:</label><br/>
    <select name="barber_id" id="barber" required>
        <?php
        $barbers_stmt = $pdo->query('SELECT id, name FROM barbers');
        $barbers = $barbers_stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($barbers as $barber) {
            echo '<option value="' . $barber['id'] . '"' . ($barber['id'] == $booking['barber_id'] ? ' selected' : '') . '>' . htmlspecialchars($barber['name']) . '</option>';
        }
        ?>
    </select><br/><br/>

    <input type="submit" value="Update Booking">
</form>

<?php
include '../includes/footer.php';
?>
