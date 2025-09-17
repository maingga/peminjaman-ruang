<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

// Ambil semua admin
$stmt = $pdo->query("SELECT * FROM admins ORDER BY id DESC");
$admins = $stmt->fetchAll();

include '../inc/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition-colors duration-500">
  <?php include 'components/navbar.php'; ?>

  <div class="flex min-h-screen">
    <?php include 'components/sidebar.php'; ?>

                    <!-- Overlay untuk mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity duration-300 pointer-events-none"></div>


    <main class="flex-1 p-8 md:p-10 overflow-auto">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold flex items-center gap-3 text-blue-700 dark:text-blue-400 select-none">
          <i data-feather="users" class="w-8 h-8"></i>
          Manajemen Admin
        </h1>
        <a href="pengguna_add.php" 
           class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-400 rounded-lg text-white font-semibold shadow-md transition duration-300 select-none">
          <i data-feather="user-plus" class="w-5 h-5"></i> Tambah Admin
        </a>
      </div>

      <!-- Flash Message -->
      <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="mb-6 px-5 py-3 bg-green-100 border border-green-300 text-green-800 rounded-lg shadow-md flex items-center justify-between select-none">
          <span class="font-medium"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></span>
          <button onclick="this.parentElement.remove()" aria-label="Close message"
            class="text-green-600 hover:text-green-800 transition duration-200">
            <i data-feather="x" class="w-5 h-5"></i>
          </button>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="mb-6 px-5 py-3 bg-red-100 border border-red-300 text-red-800 rounded-lg shadow-md flex items-center justify-between select-none">
          <span class="font-medium"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></span>
          <button onclick="this.parentElement.remove()" aria-label="Close message"
            class="text-red-600 hover:text-red-800 transition duration-200">
            <i data-feather="x" class="w-5 h-5"></i>
          </button>
        </div>
      <?php endif; ?>

      <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-2xl shadow-xl ring-1 ring-gray-200 dark:ring-gray-700">
        <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-100 dark:bg-gray-800 text-left text-gray-700 dark:text-gray-100 select-none">
            <tr>
              <th class="px-8 py-4 font-semibold">No</th>
              <th class="px-8 py-4 font-semibold">Nama</th>
              <th class="px-8 py-4 font-semibold">Email</th>
              <th class="px-8 py-4 font-semibold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            <?php if (count($admins) === 0): ?>
              <tr>
                <td colspan="4" class="px-8 py-6 text-center text-gray-500 dark:text-gray-400 font-medium">
                  Tidak ada admin yang tersedia.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($admins as $index => $admin): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                  <td class="px-8 py-4 font-medium"><?= $index + 1 ?></td>
                  <td class="px-8 py-4"><?= htmlspecialchars($admin['name']) ?></td>
                  <td class="px-8 py-4"><?= htmlspecialchars($admin['email']) ?></td>
                  <td class="px-8 py-4 text-center flex justify-center gap-5">
                    <a href="pengguna_edit.php?id=<?= $admin['id'] ?>"
                       class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-semibold transition duration-200">
                      <i data-feather="edit" class="w-5 h-5"></i> Edit
                    </a>

                    <a href="pengguna_reset.php?id=<?= $admin['id'] ?>" 
                       onclick="return confirm('Reset password admin ini menjadi 123456?')"
                       class="inline-flex items-center gap-1 text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 font-semibold transition duration-200">
                      <i data-feather="refresh-cw" class="w-5 h-5"></i> Reset
                    </a>

                    <?php if ($admin['id'] != $_SESSION['admin']['id']): ?>
                      <form action="pengguna_delete.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus admin ini?')" class="inline">
                        <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                        <button type="submit" 
                                class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-semibold transition duration-200">
                          <i data-feather="trash-2" class="w-5 h-5"></i> Hapus
                        </button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach ?>
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
