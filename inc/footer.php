<?php include __DIR__ . '/config.php'; ?>

<!-- Footer dengan tema biru Diskominfo -->
<footer class="bg-blue-900 text-white py-10 dark:bg-gray-900 dark:text-white">
  <div class="max-w-6xl mx-auto px-4 text-center">
    <img src="<?= $base_url ?>assets/images/logo-diskominfo.png" alt="Logo Diskominfo" class="mx-auto mb-4 w-20 animate-pulse" />
    <h4 class="text-xl font-semibold">Diskominfo Kabupaten XYZ</h4>
    <p class="mt-2 text-sm text-blue-200 dark:text-gray-300">Jl. Contoh Alamat No.1, Kabupaten XYZ, Indonesia</p>
    <p class="text-sm text-blue-200 dark:text-gray-300">Telp: (021) 123456 | Email: diskominfo@example.go.id</p>

    <div class="mt-6 text-xs text-blue-300 dark:text-gray-400">
      &copy; <span id="year"></span> Diskominfo XYZ. All rights reserved.
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
