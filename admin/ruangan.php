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

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition duration-300">
  <?php include 'components/navbar.php'; ?>

  <div class="flex">
    <?php include 'components/sidebar.php'; ?>

    <main class="flex-1 p-6 mt-6 md:mt-0">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold flex items-center gap-2">
          <i data-feather="layers" class="w-7 h-7 text-blue-600 dark:text-blue-400"></i>
          Daftar Ruangan
        </h1>
        <a href="tambah_ruangan.php" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 shadow">
          <i data-feather="plus" class="w-4 h-4"></i> Tambah Ruangan
        </a>
      </div>

      <!-- Flash Message -->
      <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow-md flex items-center justify-between">
          <span><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></span>
          <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
            <i data-feather="x" class="w-4 h-4"></i>
          </button>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="mb-4 px-4 py-3 bg-red-100 border border-red-300 text-red-800 rounded-lg shadow-md flex items-center justify-between">
          <span><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></span>
          <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
            <i data-feather="x" class="w-4 h-4"></i>
          </button>
        </div>
      <?php endif; ?>

      <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-xl shadow-lg ring-1 ring-gray-200 dark:ring-gray-700">
        <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-100 dark:bg-gray-800 text-left text-gray-700 dark:text-gray-100">
            <tr>
              <th class="px-6 py-4 font-medium">No</th>
              <th class="px-6 py-4 font-medium">Nama Ruangan</th>
              <th class="px-6 py-4 font-medium text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            <?php foreach ($rooms as $index => $room): ?>
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                <td class="px-6 py-4"><?= $index + 1 ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($room['name']) ?></td>
                <td class="px-6 py-4 text-center flex justify-center gap-3">
                  <a href="edit_ruangan.php?id=<?= $room['id'] ?>" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                    <i data-feather="edit" class="w-4 h-4"></i> Edit
                  </a>
                  <form action="hapus_ruangan.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus ruangan ini?')" class="inline">
                    <input type="hidden" name="id" value="<?= $room['id'] ?>">
                    <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium">
                      <i data-feather="trash-2" class="w-4 h-4"></i> Hapus
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
        <?php if (count($rooms) === 0): ?>
          <div class="px-6 py-4 text-gray-500 dark:text-gray-400 text-center">
            Tidak ada ruangan yang tersedia.
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
