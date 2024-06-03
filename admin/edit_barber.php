<?php
include '../includes/admin_header.php';
require_once '../includes/db.php';

$barber_id = $_GET['id'];
$formError = '';

// Fetch barber details
try {
    $barber_stmt = $pdo->prepare('SELECT * FROM barbers WHERE id = :barber_id');
    $barber_stmt->execute([':barber_id' => $barber_id]);
    $barber = $barber_stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching barber details: ' . $e->getMessage());
}

// Update barber
if (!empty($_POST)) {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);

    if (empty($name) || empty($contact)) {
        $formError = 'All fields are required.';
    } else {
        try {
            $update_stmt = $pdo->prepare('UPDATE barbers SET name = :name, contact = :contact WHERE id = :barber_id');
            $update_stmt->execute([
                ':name' => $name,
                ':contact' => $contact,
                ':barber_id' => $barber_id
            ]);

            header('Location: manage_barbers.php');
            exit;
        } catch (PDOException $e) {
            $formError = 'Error updating barber: ' . $e->getMessage();
        }
    }
}
?>

<h1>Edit barber details</h1>
<?php
if (!empty($formError)) {
    echo '<p style="color:red;">' . $formError . '</p>';
}
?>
<form method="post">
    <label for="name">Name:</label><br/>
    <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($barber['name']); ?>"><br/><br/>

    <label for="contact">Contact:</label><br/>
    <input type="text" name="contact" id="contact" required value="<?php echo htmlspecialchars($barber['contact']); ?>"><br/><br/>

    <input type="submit" value="Update Barber">
</form>

<?php
include '../includes/footer.php';
?>
