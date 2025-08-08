<?php
require_once '../inc/db.php';
require_once '../inc/auth.php';

$status  = $_GET['status'] ?? '';
$ruangan = $_GET['ruangan'] ?? '';

$where = [];
$params = [];

if (!empty($status)) {
    $where[] = "b.status = ?";
    $params[] = $status;
}
if (!empty($ruangan)) {
    $where[] = "b.room_id = ?";
    $params[] = $ruangan;
}

$sql = "SELECT b.*, r.name AS room_name
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$events = [];
foreach ($data as $row) {
    $color = ($row['status'] == 'approved') ? '#16a34a' : (($row['status'] == 'pending') ? '#facc15' : '#dc2626');
    $events[] = [
        'id' => $row['id'],
        'title' => $row['name'],
        'start' => $row['date'] . 'T' . $row['start_time'],
        'end' => $row['date'] . 'T' . $row['end_time'],
        'color' => $color,
        'room' => $row['room_name']
    ];
}

header('Content-Type: application/json');
echo json_encode($events);
