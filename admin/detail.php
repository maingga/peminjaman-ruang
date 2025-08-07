<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: peminjaman.php");
  exit;
}

$booking_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT b.*, r.name AS room_name FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.id = ?");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch();

if (!$booking) {
  header("Location: peminjaman.php");
  exit;
}

include '../inc/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition duration-300">
  <?php include 'components/navbar.php'; ?>

  <div class="flex">
    <?php include 'components/sidebar.php'; ?>

    <main class="flex-1 p-6 mt-4 md:mt-0">
      <h1 class="text-3xl font-semibold mb-6 flex items-center gap-3">
        <i data-feather="info" class="w-8 h-8 text-blue-600"></i>
        Detail Peminjaman
      </h1>

      <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 space-y-4">
        <div class="grid md:grid-cols-2 gap-4 text-sm md:text-base">
          <p><strong>Nama:</strong> <?= htmlspecialchars($booking['name']) ?></p>
          <p><strong>Dinas:</strong> <?= htmlspecialchars($booking['dinas']) ?></p>
          <p><strong>Bidang:</strong> <?= htmlspecialchars($booking['bidang']) ?></p>
          <p><strong>No. HP:</strong> <?= htmlspecialchars($booking['phone']) ?></p>
          <p><strong>Ruangan:</strong> <?= htmlspecialchars($booking['room_name']) ?></p>
          <p><strong>Tanggal:</strong> <?= htmlspecialchars($booking['date']) ?></p>
          <p><strong>Waktu:</strong> <?= htmlspecialchars($booking['start_time']) ?> - <?= htmlspecialchars($booking['end_time']) ?></p>
          <p><strong>Diajukan pada:</strong> <?= htmlspecialchars($booking['created_at']) ?></p>
        </div>

        <div class="mt-2">
          <strong>Status:</strong>
          <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full 
            <?= $booking['status'] === 'approved' ? 'bg-green-100 text-green-700' :
                ($booking['status'] === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') ?>">
            <i data-feather="<?= $booking['status'] === 'approved' ? 'check-circle' : ($booking['status'] === 'rejected' ? 'x-circle' : 'clock') ?>" class="w-4 h-4 mr-1"></i>
            <?= ucfirst($booking['status']) ?>
          </span>
        </div>

        <?php if ($booking['status'] === 'pending'): ?>
          <div class="mt-6 flex gap-4 flex-wrap">
            <form action="approve.php" method="POST" onsubmit="return confirm('Yakin ingin menyetujui peminjaman ini?')">
              <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
              <input type="hidden" name="action" value="approve">
              <button type="submit" class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-sm transition">
                <i data-feather="check" class="w-4 h-4"></i> Setujui
              </button>
            </form>

            <form action="approve.php" method="POST" onsubmit="return confirm('Yakin ingin menolak peminjaman ini?')">
              <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
              <input type="hidden" name="action" value="reject">
              <button type="submit" class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-sm transition">
                <i data-feather="x" class="w-4 h-4"></i> Tolak
              </button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    </main>
  </div>
</div>

<script>
  feather.replace();
</script>

<?php include '../inc/footer.php'; ?>
