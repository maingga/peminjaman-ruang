<?php
session_start();
require_once '../inc/db.php';

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Hanya izinkan akses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: peminjaman.php");
    exit;
}

// Ambil data dari form
$id     = $_POST['id'] ?? null;
$action = $_POST['action'] ?? null;

// Validasi input
if (!$id || !in_array($action, ['approve', 'reject'])) {
    header("Location: peminjaman.php");
    exit;
}

// Ambil detail booking
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->execute([$id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("❌ Data peminjaman tidak ditemukan.");
}

// Pastikan status masih pending
if ($booking['status'] !== 'pending') {
    header("Location: peminjaman.php");
    exit;
}

if ($action === 'approve') {
    // Cek bentrok sebelum approve (logika lengkap dari kode pertama)
    $stmt = $pdo->prepare("
        SELECT * FROM bookings 
        WHERE room_id = ? 
          AND date = ? 
          AND status = 'approved'
          AND id != ?
          AND (
            (start_time <= ? AND end_time > ?)  
            OR (start_time < ? AND end_time >= ?) 
            OR (start_time >= ? AND end_time <= ?) 
          )
    ");
    $stmt->execute([
        $booking['room_id'], 
        $booking['date'], 
        $booking['id'],
        $booking['start_time'], $booking['start_time'], 
        $booking['end_time'], $booking['end_time'],     
        $booking['start_time'], $booking['end_time']    
    ]);

    if ($stmt->fetch()) {
        echo "<script>
            alert('❌ Tidak dapat menyetujui. Jadwal bentrok dengan peminjaman lain.');
            window.location.href = 'peminjaman.php';
        </script>";
        exit;
    }

    // Set status approved
    $update = $pdo->prepare("UPDATE bookings SET status = 'approved' WHERE id = ?");
    $update->execute([$id]);

} elseif ($action === 'reject') {
    // Set status rejected
    $update = $pdo->prepare("UPDATE bookings SET status = 'rejected' WHERE id = ?");
    $update->execute([$id]);
}

// Redirect kembali setelah aksi
header("Location: peminjaman.php");
exit;
