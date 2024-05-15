<?php
require_once 'includes/db.php';

$barber_id = $_GET['barber_id'];

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare('SELECT DISTINCT date 
                           FROM schedules 
                           WHERE barber_id = :barber_id AND is_available = 1 AND date >= CURDATE() 
                           ORDER BY date');
    $stmt->execute([':barber_id' => $barber_id]);
    $dates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($dates);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error fetching dates: ' . $e->getMessage()]);
}
