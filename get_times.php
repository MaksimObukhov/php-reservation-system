<?php
require_once 'includes/db.php';

$barber_id = $_GET['barber_id'];
$date = $_GET['date'];

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare('SELECT id, time 
                           FROM schedules 
                           WHERE barber_id = :barber_id AND date = :date AND is_available = 1 
                           ORDER BY time');
    $stmt->execute([':barber_id' => $barber_id, ':date' => $date]);
    $times = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($times);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error fetching times: ' . $e->getMessage()]);
}
