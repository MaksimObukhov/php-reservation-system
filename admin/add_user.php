<?php
include '../includes/admin_header.php';
require_once '../includes/db.php';

$formError = '';

if (!empty($_POST)) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $formError = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = 'Invalid email address.';
    } elseif ($password !== $confirmPassword) {
        $formError = 'Passwords do not match.';
    } else {
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, phone, password) VALUES (:name, :email, :phone, :password)');
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':password' => $passwordHash
            ]);

            header('Location: manage_users.php');
            exit;
        } catch (PDOException $e) {
            $formError = 'Error adding user: ' . $e->getMessage();
        }
    }
}
?>

<h1>Add new user</h1>
<?php
if (!empty($formError)) {
    echo '<p style="color:red;">' . $formError . '</p>';
}
?>
<form method="post">
    <label for="name">Name:</label><br/>
    <input type="text" name="name" id="name" required><br/><br/>

    <label for="email">Email:</label><br/>
    <input type="email" name="email" id="email" required><br/><br/>

    <label for="phone">Phone:</label><br/>
    <input type="text" name="phone" id="phone"><br/><br/>

    <label for="password">Password:</label><br/>
    <input type="password" name="password" id="password" required><br/><br/>

    <label for="confirm_password">Confirm Password:</label><br/>
    <input type="password" name="confirm_password" id="confirm_password" required><br/><br/>

    <input type="submit" value="Add User">
</form>

<?php
include '../includes/footer.php';
?>
