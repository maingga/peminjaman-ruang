<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

include '../inc/header.php';
?>

<div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-100 via-white to-blue-200 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition duration-300">

  <?php include 'components/navbar.php'; ?>

  <div class="flex flex-1 relative">

    <?php include 'components/sidebar.php'; ?>

    <!-- Overlay untuk mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity duration-300"></div>

    <main class="flex-1 p-6 animate-fadeInUp mt-4 md:mt-0 z-10 relative">
      <h1 class="text-3xl font-bold mb-6 flex items-center gap-2">
        <i data-feather="activity" class="w-7 h-7 text-blue-500"></i>
        Selamat Datang, <?= htmlspecialchars($_SESSION['admin']['name']) ?>
      </h1>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <?php
          require 'components/statistik-box.php';
          statistikBox('Total Peminjaman', 'clipboard', 'blue');
          statistikBox('Ruangan Tersedia', 'check-square', 'green');
          statistikBox('Menunggu Persetujuan', 'clock', 'yellow');
        ?>
      </div>

      <?php include 'components/countdown-box.php'; ?>
    </main>

  </div>
</div>

<!-- Feather Icons -->
<script>
  feather.replace();
</script>

<!-- Script interaksi dashboard -->
<script src="../assets/js/dashboard.js"></script>

<?php include '../inc/footer.php'; ?>
