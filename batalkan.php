<?php
require_once 'inc/db.php';

$token = $_GET['token'] ?? null;
$success = $error = null;

if ($token) {
    // Ambil booking berdasarkan token
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE cancel_token = ?");
    $stmt->execute([$token]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        $error = "❌ Token pembatalan tidak valid.";
    } elseif ($booking['status'] !== 'pending') {
        $error = "❌ Booking tidak bisa dibatalkan (sudah diproses admin).";
    }

    // Proses pembatalan jika konfirmasi YES
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE cancel_token = ?")
            ->execute([$token]);
        $success = "✅ Booking berhasil dibatalkan.";
    }
} else {
    $error = "❌ Token pembatalan tidak ditemukan.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembatalan Booking</title>
<style>
    body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 20px; text-align: center; }
    .card { max-width: 400px; margin: auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .btn { padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px; }
    .btn-danger { background: #dc3545; color: white; }
    .btn-secondary { background: #6c757d; color: white; }
    .message { margin-bottom: 20px; }
</style>
</head>
<body>
<div class="card">
    <h2>Pembatalan Booking</h2>

    <?php if ($error): ?>
        <p class="message" style="color: red;"><?= $error ?></p>
    <?php elseif ($success): ?>
        <p class="message" style="color: green;"><?= $success ?></p>
    <?php elseif ($booking): ?>
        <p>Apakah Anda yakin ingin membatalkan booking untuk:</p>
        <p><b><?= htmlspecialchars($booking['name']) ?></b><br>
           <?= htmlspecialchars($booking['date']) ?> (<?= htmlspecialchars($booking['start_time']) ?> - <?= htmlspecialchars($booking['end_time']) ?>)</p>
        <form method="post">
            <button type="submit" name="confirm" value="yes" class="btn btn-danger">Ya, Batalkan</button>
            <a href="index.php" class="btn btn-secondary">Tidak</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
