<?php
include '../includes/admin_header.php';
require_once '../includes/db.php';

$user_id = $_GET['id'];
$formError = '';

// Fetch user details
try {
    $user_stmt = $pdo->prepare('SELECT * FROM users WHERE id = :user_id');
    $user_stmt->execute([':user_id' => $user_id]);
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching user details: ' . $e->getMessage());
}

// Update user
if (!empty($_POST)) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    if (empty($name) || empty($email)) {
        $formError = 'Name and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = 'Invalid email address.';
    } else {
        try {
            // If password is provided, update it
            if (!empty($password)) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare('UPDATE users SET name = :name, email = :email, phone = :phone, password = :password WHERE id = :user_id');
                $update_stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':password' => $passwordHash,
                    ':user_id' => $user_id
                ]);
            } else {
                // Otherwise, update without changing the password
                $update_stmt = $pdo->prepare('UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :user_id');
                $update_stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':user_id' => $user_id
                ]);
            }

            header('Location: manage_users.php');
            exit;
        } catch (PDOException $e) {
            $formError = 'Error updating user: ' . $e->getMessage();
        }
    }
}
?>

<h1>Edit user</h1>
<?php
if (!empty($formError)) {
    echo '<p style="color:red;">' . $formError . '</p>';
}
?>
<form method="post">
    <label for="name">Name:</label><br/>
    <input type="text" name="name" id="name" required value="<?php echo htmlspecialchars($user['name']); ?>"><br/><br/>

    <label for="email">Email:</label><br/>
    <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($user['email']); ?>"><br/><br/>

    <label for="phone">Phone:</label><br/>
    <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br/><br/>

    <label for="password">Password (leave blank to keep current password):</label><br/>
    <input type="password" name="password" id="password"><br/><br/>

    <input type="submit" value="Update User">
</form>

<?php
include '../includes/footer.php';
?>
