<?php
session_start();
require_once '../inc/db.php';

// Cek jika belum login sebagai admin
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

// Ambil data booking berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->execute([$id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

// Cek apakah data booking ditemukan
if (!$booking) {
  die("❌ Data peminjaman tidak ditemukan.");
}

// Cek apakah status masih 'pending'
if ($booking['status'] !== 'pending') {
  header("Location: peminjaman.php");
  exit;
}

if ($action === 'approve') {
  // Cek apakah ada konflik jadwal
  $conflictQuery = "SELECT * FROM bookings 
                    WHERE room_id = :room_id 
                      AND date = :date 
                      AND status = 'approved'
                      AND (
                        (:start_time BETWEEN start_time AND end_time)
                        OR (:end_time BETWEEN start_time AND end_time)
                        OR (start_time BETWEEN :start_time AND :end_time)
                      )";

  $conflictCheck = $pdo->prepare($conflictQuery);
  $conflictCheck->execute([
    ':room_id'    => $booking['room_id'],
    ':date'       => $booking['date'],
    ':start_time' => $booking['start_time'],
    ':end_time'   => $booking['end_time']
  ]);

  if ($conflictCheck->rowCount() > 0) {
    // ❌ Jadwal bentrok — tampilkan alert dan redirect
    echo "<script>
      alert('❌ Tidak dapat menyetujui. Jadwal bentrok dengan peminjaman lain.');
      window.location.href = 'peminjaman.php';
    </script>";
    exit;
  }

  // ✅ Tidak bentrok — setujui
  $update = $pdo->prepare("UPDATE bookings SET status = 'approved' WHERE id = ?");
  $update->execute([$id]);

} elseif ($action === 'reject') {
  // ❌ Ditolak langsung
  $update = $pdo->prepare("UPDATE bookings SET status = 'rejected' WHERE id = ?");
  $update->execute([$id]);
}

// ✅ Selesai — redirect kembali
header("Location: peminjaman.php");
exit;
