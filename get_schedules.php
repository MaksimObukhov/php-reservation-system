<?php
require_once 'includes/db.php';

$barber_id = $_GET['barber_id'];

header('Content-Type: application/json');

if ($barber_id) {
    try {
      $stmt = $pdo->prepare('SELECT id, date, time 
                                 FROM schedules 
                                 WHERE barber_id = :barber_id AND date >= CURDATE() AND is_available = TRUE 
                                 ORDER BY date, time');
      $stmt->execute(['barber_id' => $barber_id]);
      $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
      echo json_encode($schedules);
    } catch (PDOException $e) {
      echo json_encode(['error' => 'Error fetching schedules: ' . $e->getMessage()]);
    }
} else {
    echo json_encode([]);
}
