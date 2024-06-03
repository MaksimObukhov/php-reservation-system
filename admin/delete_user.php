<?php
require_once '../includes/db.php';

$user_id = $_GET['id'];

try {
    $delete_stmt = $pdo->prepare('DELETE FROM users WHERE id = :user_id');
    $delete_stmt->execute([':user_id' => $user_id]);

    header('Location: manage_users.php');
    exit;
} catch (PDOException $e) {
    die('Error deleting user: ' . $e->getMessage());
}
