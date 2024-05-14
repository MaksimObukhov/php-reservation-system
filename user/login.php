<?php
// Start session
session_start();

require_once '../includes/db.php';
include '../includes/header.php';

$formError = '';

if (!empty($_POST)) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate form input
    if (empty($email) || empty($password)) {
    $formError = 'Both fields are required.';
    } else {
        // Check if the user exists and the password is correct
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);

        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existingUser && password_verify($password, $existingUser['password'])) {
            // Log in the user
            $_SESSION['user_id'] = $existingUser['id'];
            header('Location: ../index.php');
            exit;
        } else {
            $formError = 'Invalid email or password.';
        }
    }
}
?>

<h2>Sign in</h2>

<?php
if (!empty($formError)){
    echo '<p style="color:red;">'.$formError.'</p>';
}
?>

<form method="post">
    <label for="email">Your Email:</label><br/>
    <input type="email" name="email" id="email" required><br/><br/>

    <label for="password">Password:</label><br/>
    <input type="password" name="password" id="password" required><br/><br/>

    <input type="submit" value="Sign in">
</form>

<br/>

<a href="register.php">Don't have an account yet? Sign up!</a>

<?php
include '../includes/footer.php';
?>
