<?php
require_once 'includes/db.php';
include 'includes/header.php';

// Fetch barbers
try {
  $barbers_stmt = $pdo->query('SELECT id, name FROM barbers');
  $barbers = $barbers_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die('Error fetching barbers: ' . $e->getMessage());
}
?>

<h2>Book an Appointment</h2>
<form action="confirm_booking.php" method="POST" id="bookingForm">
    <label for="name">Name:</label><br/>
    <input type="text" id="name" name="name" required><br/><br/>

    <label for="email">Email:</label><br/>
    <input type="email" id="email" name="email" required><br/><br/>

    <label for="phone">Phone:</label><br/>
    <input type="text" id="phone" name="phone" required><br/><br/>
    <label for="barber">Choose a barber:</label><br/>
    <select id="barber" name="barber_id" required>
        <option value="">Select a barber</option>
      <?php foreach ($barbers as $barber): ?>
          <option value="<?php echo $barber['id']; ?>"><?php echo $barber['name']; ?></option>
      <?php endforeach; ?>
    </select><br/><br/>

    <label for="schedule">Choose a time slot:</label><br/>
    <select id="schedule" name="schedule_id" required>
        <option value="">Select a time slot</option>
    </select><br/><br/>

    <button type="submit">Book Appointment</button>
</form>

<script src="js/booking.js"></script>

<?php
include 'includes/footer.php';
?>
