<?php
// Start session
session_start();

require_once '../includes/db.php';
include '../includes/header.php';

$formError = '';

if (!empty($_POST)) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $phone = trim($_POST['phone']);

    // Validate form input
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword) || empty($phone)) {
        $formError = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = 'Invalid email address.';
    } elseif ($password !== $confirmPassword) {
        $formError = 'Passwords do not match.';
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
            $formError = 'Email is already registered.';
        } else {
            // Hash the password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) 
                        VALUES (:name, :email, :phone, :passwordHash)");
            $stmt->execute([
                ':name' => $name,
                ':email' =>  $email,
                ':phone' => $phone,
                ':passwordHash' => $passwordHash
            ]);

            // Get the new user's ID and log them in
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $_SESSION['user_id'] = (int)$stmt->fetchColumn();

            // Redirect to homepage
            header('Location: login.php');
            exit;
        }
    }
}
?>

<h2>New Signup</h2>

<?php
if (!empty($formError)){
    echo '<p style="color:red;">'.$formError.'</p>';
}
?>

<form method="post">
    <label for="name">Name:</label><br/>
    <input type="text" name="name" id="name" required><br/><br/>

    <label for="email">Email:</label><br/>
    <input type="email" name="email" id="email" required><br/><br/>

    <label for="phone">Phone:</label><br/>
    <input type="text" name="phone" id="phone" required><br/><br/>

    <label for="password">New Password:</label><br/>
    <input type="password" name="password" id="password" required><br/><br/>

    <label for="confirm_password">Confirm Password:</label><br/>
    <input type="password" name="confirm_password" id="confirm_password" required><br/><br/>

    <input type="submit" value="Create Account"> or <a href="../index.php">Cancel</a>
</form>

<?php
include '../includes/footer.php';
?>
