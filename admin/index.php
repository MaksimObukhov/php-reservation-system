<?php
session_start();
require_once '../includes/db.php';

$formError = '';

if (!empty($_POST)) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate form input
    if (empty($email) || empty($password)) {
        $formError = 'Both fields are required.';
    } else {
        // Check if the admin exists and the password is correct
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);

        $existingAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existingAdmin && password_verify($password, $existingAdmin['password'])) {
            // Log in the admin
            $_SESSION['admin_id'] = $existingAdmin['id'];
            header('Location: manage_bookings.php');
            exit;
        } else {
            $formError = 'Invalid email or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<h1>Admin Login</h1>
<?php
if (!empty($formError)) {
    echo '<p style="color:red;">' . $formError . '</p>';
}
?>
<form method="post">
    <label for="email">Email:</label><br/>
    <input type="email" name="email" id="email" required><br/><br/>

    <label for="password">Password:</label><br/>
    <input type="password" name="password" id="password" required><br/><br/>

    <input type="submit" value="Login">
</form>
</body>
</html>
