<?php
require_once '../inc/auth.php';
require_once '../inc/db.php';

if (isset($_GET['id']) && isset($_GET['act'])) {
    $id = $_GET['id'];
    $act = $_GET['act'];

    if ($act === 'approve' || $act === 'reject') {
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$act, $id]);
    }
}

header("Location: dashboard.php");
exit;
