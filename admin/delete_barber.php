<?php
require_once '../includes/db.php';

$barber_id = $_GET['id'];

try {
    $delete_stmt = $pdo->prepare('DELETE FROM barbers WHERE id = :barber_id');
    $delete_stmt->execute([':barber_id' => $barber_id]);

    header('Location: manage_barbers.php');
    exit;
} catch (PDOException $e) {
    die('Error deleting barber: ' . $e->getMessage());
}
