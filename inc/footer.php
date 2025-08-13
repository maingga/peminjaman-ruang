<?php include __DIR__ . '/config.php'; ?>

<!-- Footer dengan tema biru Diskominfo -->
<footer class="bg-blue-900 text-white py-10 dark:bg-gray-900 dark:text-white">
  <div class="max-w-6xl mx-auto px-4 text-center">
    <img src="<?= $base_url ?>assets/images/logo-diskominfo.png" alt="Logo Diskominfo" class="mx-auto mb-4 w-20 animate-pulse" />
    <h4 class="text-xl font-semibold">Dinas Komunikasi dan Informatika Kabupaten Kediri</h4>
    <p class="mt-2 text-sm text-blue-200 dark:text-gray-300">Jl. Sekartaji No.2, Sumber, Doko, Kec. Ngasem, Kabupaten Kediri, Jawa Timur 64182</p>
    <p class="text-sm text-blue-200 dark:text-gray-300">Telp:  (0354) 682152 | Email: diskominfo@kedirikab.go.id</p>

    <div class="mt-6 text-xs text-blue-300 dark:text-gray-400">
      &copy; <span id="year"></span> Dinas Komunikasi dan Informatika Kabupaten Kediri. All rights reserved.
    </div>
  </div>
</footer>

<!-- Tombol Toggle Dark Mode -->
<button id="darkToggle"
  aria-label="Toggle Dark Mode"
  class="fixed bottom-5 right-5 bg-blue-700 text-white p-3 rounded-full shadow-lg z-50 transition hover:scale-105 hover:bg-blue-800 dark:bg-yellow-500 dark:hover:bg-yellow-400 dark:text-black">
  🌙
</button>

<!-- Script Global -->
<script src="<?= $base_url ?>assets/js/footer.js"></script>

<!-- Script Khusus Per Halaman -->
<?php
$currentPage = basename($_SERVER['PHP_SELF']);

// Tambah script khusus tiap halaman sesuai kebutuhan
$scriptMap = [
  'ajukan.php' => 'ajukan.js',
  'login.php' => 'login.js',
  'dashboard.php' => 'dashboard.js',
  'riwayat.php' => 'riwayat.js',
  'jadwal.php' => 'jadwal.js'
];

if (array_key_exists($currentPage, $scriptMap)) {
  echo '<script src="' . $base_url . 'assets/js/' . $scriptMap[$currentPage] . '" defer></script>';
}
?>

<!-- Set tahun otomatis -->
<script>
  document.getElementById('year').textContent = new Date().getFullYear();
</script>

</body>
</html>
