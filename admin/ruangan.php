<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

$stmt = $pdo->query("SELECT * FROM rooms ORDER BY id DESC");
$rooms = $stmt->fetchAll();

include '../inc/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition-colors duration-500">
  <?php include 'components/navbar.php'; ?>

  <div class="flex min-h-screen">
    <?php include 'components/sidebar.php'; ?>

            <!-- Overlay untuk mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity duration-300 pointer-events-none"></div>


    <main class="flex-1 p-6 md:p-10 overflow-auto">
      <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <h1 class="text-3xl font-extrabold flex items-center gap-3 text-blue-700 dark:text-blue-400 select-none">
          <i data-feather="layers" class="w-8 h-8"></i>
          Daftar Ruangan
        </h1>
        <a href="tambah_ruangan.php" 
           class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-400 text-white rounded-lg shadow-md transition select-none font-semibold">
          <i data-feather="plus" class="w-5 h-5"></i> Tambah Ruangan
        </a>
      </div>

      <!-- Flash Messages -->
      <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="mb-6 px-6 py-4 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow-md flex items-center justify-between max-w-3xl mx-auto">
          <span><?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></span>
          <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 focus:outline-none" aria-label="Close">
            <i data-feather="x" class="w-5 h-5"></i>
          </button>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="mb-6 px-6 py-4 bg-red-100 border border-red-300 text-red-800 rounded-lg shadow-md flex items-center justify-between max-w-3xl mx-auto">
          <span><?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></span>
          <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 focus:outline-none" aria-label="Close">
            <i data-feather="x" class="w-5 h-5"></i>
          </button>
        </div>
      <?php endif; ?>

      <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-2xl shadow-2xl ring-1 ring-gray-200 dark:ring-gray-700 max-w-6xl mx-auto">
        <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-100 dark:bg-gray-800 text-left text-gray-700 dark:text-gray-100 uppercase tracking-wide select-none">
            <tr>
              <th class="px-6 py-4 font-semibold">No</th>
              <th class="px-6 py-4 font-semibold">Nama Ruangan</th>
              <th class="px-6 py-4 font-semibold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            <?php if (count($rooms) === 0): ?>
              <tr>
                <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 italic">
                  Tidak ada ruangan yang tersedia.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($rooms as $index => $room): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                  <td class="px-6 py-4 whitespace-nowrap font-medium"><?= $index + 1 ?></td>
                  <td class="px-6 py-4 whitespace-normal max-w-xs break-words"><?= htmlspecialchars($room['name']) ?></td>
                  <td class="px-6 py-4 text-center flex justify-center gap-5 whitespace-nowrap">
                    <a href="edit_ruangan.php?id=<?= $room['id'] ?>"
                       class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-semibold transition">
                      <i data-feather="edit" class="w-5 h-5"></i> Edit
                    </a>
                    <form action="hapus_ruangan.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus ruangan ini?')" class="inline">
                      <input type="hidden" name="id" value="<?= $room['id'] ?>">
                      <button type="submit"
                        class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-semibold transition">
                        <i data-feather="trash-2" class="w-5 h-5"></i> Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<script>
  feather.replace();
</script>

<!-- Script interaksi dashboard -->
<script src="../assets/js/dashboard.js"></script>


<?php include '../inc/footer.php'; ?>
