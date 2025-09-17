<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

include '../inc/header.php';

// 🔹 Ambil data statistik dari database
$totalPeminjaman = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$totalRuangan    = $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
$pendingApproval = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn();
?>

<div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-100 via-white to-blue-200 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition-colors duration-300">

  <?php include 'components/navbar.php'; ?>

  <div class="flex flex-1 relative">

    <?php include 'components/sidebar.php'; ?>

    <!-- Overlay untuk mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity duration-300 pointer-events-none"></div>

    <main class="flex-1 p-6 animate-fadeInUp mt-4 md:mt-0 z-10 relative max-w-7xl mx-auto">
      <h1 class="text-3xl font-extrabold mb-6 flex items-center gap-3 select-none text-blue-600 dark:text-blue-400">
        <i data-feather="activity" class="w-7 h-7"></i>
        Selamat Datang, <span class="capitalize"><?= htmlspecialchars($_SESSION['admin']['name']) ?></span>
      </h1>

      <!-- Statistik Box -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <?php
          require 'components/statistik-box.php';
          statistikBox('Total Peminjaman', 'clipboard', 'blue', $totalPeminjaman);
          statistikBox('Ruangan Tersedia', 'check-square', 'green', $totalRuangan);
          statistikBox('Menunggu Persetujuan', 'clock', 'yellow', $pendingApproval);
        ?>
      </div>

      <?php include 'components/countdown-box.php'; ?>
    </main>

  </div>
</div>

<!-- Feather Icons -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
  feather.replace();
</script>

<!-- Script interaksi dashboard -->
<script src="../assets/js/dashboard.js"></script>

<?php include '../inc/footer.php'; ?>
