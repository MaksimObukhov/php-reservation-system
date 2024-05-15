<?php
include '../includes/admin_header.php';
require_once '../includes/db.php';

$formError = '';

// Add new schedule
if (!empty($_POST)) {
    $barber_id = $_POST['barber_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    if (empty($barber_id) || empty($date) || empty($time)) {
        $formError = 'All fields are required.';
    } else {
        try {
            // Check if the schedule already exists
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM schedules WHERE barber_id = :barber_id AND date = :date AND time = :time');
            $stmt->execute([
                ':barber_id' => $barber_id,
                ':date' => $date,
                ':time' => $time
            ]);

            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $formError = 'The barber already has a schedule at this date and time.';
            } else {
                // Insert new schedule
                $stmt = $pdo->prepare('INSERT INTO schedules (barber_id, date, time, is_available) 
                            VALUES (:barber_id, :date, :time, TRUE)');
                $stmt->execute([
                    ':barber_id' => $barber_id,
                    ':date' => $date,
                    ':time' => $time
                ]);

                header('Location: manage_bookings.php');
                exit;
            }
        } catch (PDOException $e) {
            $formError = 'Error adding schedule: ' . $e->getMessage();
        }
    }
}
?>

<h1>Add schedule</h1>
<?php
if (!empty($formError)) {
    echo '<p style="color:red;">' . $formError . '</p>';
}
?>
<form method="post">
    <label for="barber">Barber:</label><br/>
    <select name="barber_id" id="barber" required>
        <?php
        $barbers_stmt = $pdo->query('SELECT id, name FROM barbers');
        $barbers = $barbers_stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($barbers as $barber) {
            echo '<option value="' . $barber['id'] . '">' . htmlspecialchars($barber['name']) . '</option>';
        }
        ?>
    </select><br/><br/>

    <label for="date">Date:</label><br/>
    <input type="date" name="date" id="date" required><br/><br/>

    <label for="time">Time:</label><br/>
    <input type="time" name="time" id="time" required><br/><br/>

    <input type="submit" value="Add Schedule">
</form>

<?php
include '../includes/footer.php';
?>
