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

  <div class="flex flex-1">
    <?php include 'components/sidebar.php'; ?>

    <main class="flex-1 p-6 animate-fadeInUp mt-4 md:mt-0">
      <h1 class="text-3xl font-bold mb-6 flex items-center gap-2">
        <i data-feather="activity" class="w-7 h-7 text-blue-500"></i> Selamat Datang, <?= htmlspecialchars($_SESSION['admin']['name']) ?>
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

<script>
  feather.replace();
  const sidebar = document.getElementById('sidebar');
  const openBtn = document.getElementById('openSidebar');
  const closeBtn = document.getElementById('closeSidebar');

  if (openBtn && closeBtn && sidebar) {
    openBtn.addEventListener('click', () => {
      sidebar.classList.remove('-translate-x-full');
    });
    closeBtn.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
    });
  }
</script>

<?php include '../inc/footer.php'; ?>
