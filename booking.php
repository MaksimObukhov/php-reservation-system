<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

try {
    $barbers_stmt = $pdo->query('SELECT id, name FROM barbers');
    $barbers = $barbers_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching barbers: ' . $e->getMessage());
}

$name = '';
$email = '';
$phone = '';

// Check if user is logged in and fetch user details
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    try {
        $user_stmt = $pdo->prepare('SELECT name, email, phone FROM users WHERE id = :user_id LIMIT 1');
        $user_stmt->execute([':user_id' => $user_id]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $name = htmlspecialchars($user['name']);
            $email = htmlspecialchars($user['email']);
            $phone = htmlspecialchars($user['phone']);
        }
    } catch (PDOException $e) {
        die('Error fetching user details: ' . $e->getMessage());
    }
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if (empty($errors)) {
        $_SESSION['booking_data'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'schedule_id' => $schedule_id,
        ];
        header('Location: confirm_booking.php');
        exit;
    }
}
?>

<h2>Book an Appointment</h2>
<form action="booking.php" method="POST" id="bookingForm">
    <label for="name">Name:</label><br/>
    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($name); ?>"><br/><br/>
    <?php if (isset($errors['name'])): ?><p><?php echo $errors['name']; ?></p><?php endif; ?>

    <label for="email">Email:</label><br/>
    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>"><br/><br/>
    <?php if (isset($errors['email'])): ?><p><?php echo $errors['email']; ?></p><?php endif; ?>

    <label for="phone">Phone:</label><br/>
    <input type="text" id="phone" name="phone" required value="<?php echo htmlspecialchars($phone); ?>"><br/><br/>
    <?php if (isset($errors['phone'])): ?><p><?php echo $errors['phone']; ?></p><?php endif; ?>

    <label for="barber">Choose a barber:</label><br/>
    <select id="barber" name="barber_id" required>
        <option value="">Select a barber</option>
        <?php foreach ($barbers as $barber): ?>
            <option value="<?php echo $barber['id']; ?>"><?php echo htmlspecialchars($barber['name']); ?></option>
        <?php endforeach; ?>
    </select><br/><br/>

    <label for="schedule">Choose a time slot:</label><br/>
    <select id="schedule" name="schedule_id" required>
        <option value="">Select a time slot</option>
    </select><br/><br/>
    <?php if (isset($errors['schedule_id'])): ?><p><?php echo $errors['schedule_id']; ?></p><?php endif; ?>

    <button type="submit">Book Appointment</button>
</form>

<script src="js/booking.js"></script>

<?php
include 'includes/footer.php';
?>
