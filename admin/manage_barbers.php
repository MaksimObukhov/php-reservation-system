<?php
include '../includes/admin_header.php';
require_once '../includes/db.php';

try {
    $barbers_stmt = $pdo->query('SELECT * FROM barbers');
    $barbers = $barbers_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error fetching barbers: ' . $e->getMessage());
}
?>

<h1>Manage barbers</h1>
<a href="add_barber.php">Add new barber</a><br/><br/>
<table>
    <tr>
        <th>Name</th>
        <th>Contact</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($barbers as $barber): ?>
        <tr>
            <td><?php echo htmlspecialchars($barber['name']); ?></td>
            <td><?php echo htmlspecialchars($barber['contact']); ?></td>
            <td>
                <a href="edit_barber.php?id=<?php echo $barber['id']; ?>">Edit</a>
                <form method="post" action="delete_barber.php" style="display:inline;">
                    <input type="hidden" name="barber_id" value="<?php echo $barber['id']; ?>">
                    <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this barber?');">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/footer.php';
?>
