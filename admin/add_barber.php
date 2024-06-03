<?php
include '../includes/admin_header.php';
require_once '../includes/db.php';

$formError = '';

if (!empty($_POST)) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];

    if (empty($name)) {
        $formError = 'Name is required.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO barbers (name, contact) VALUES (:name, :contact)');
            $stmt->execute([
                ':name' => $name,
                ':contact' => $contact
            ]);
            header('Location: manage_barbers.php');
            exit;
        } catch (PDOException $e) {
            $formError = 'Error adding barber: ' . $e->getMessage();
        }
    }
}
?>

<h1>Add new barber</h1>
<?php
if (!empty($formError)) {
    echo '<p style="color:red;">' . $formError . '</p>';
}
?>
<form method="post">
    <label for="name">Name:</label><br/>
    <input type="text" id="name" name="name" required><br/><br/>

    <label for="contact">Email:</label><br/>
    <input type="email" id="email" name="email"><br/><br/>

    <input type="submit" value="Add Barber">
</form>

<?php
include '../includes/footer.php';
?>
