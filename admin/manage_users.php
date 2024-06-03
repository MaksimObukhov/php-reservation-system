<?php
include '../includes/admin_header.php';
require_once '../includes/db.php';

// Fetch all users
try {
    $users_stmt = $pdo->query('SELECT * FROM users');
    $users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching users: ' . $e->getMessage());
}
?>

<h1>Manage users</h1>
<a href="add_user.php">Add new user</a><br/><br/>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['phone']); ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                <form method="post" action="delete_user.php" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/footer.php';
?>
