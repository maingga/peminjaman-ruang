<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$success = '';

// Ambil data ruangan berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $id]);
$room = $stmt->fetch();

if (!$room) {
  die("Ruangan tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);

  if ($name === '') {
    $error = 'Nama ruangan tidak boleh kosong.';
  } else {
    $update = $pdo->prepare("UPDATE rooms SET name = :name WHERE id = :id");
    $update->execute([
      'name' => $name,
      'id' => $id
    ]);

    $success = 'Data ruangan berhasil diperbarui.';
    header("Location: ruangan.php?updated=1");
    exit;
  }
}

include '../inc/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-white via-blue-100 to-white dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition-all duration-300">
  <?php include 'components/navbar.php'; ?>

  <div class="flex">
    <?php include 'components/sidebar.php'; ?>

                    <!-- Overlay untuk mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity duration-300 pointer-events-none"></div>


    <main class="flex-1 px-4 py-6 sm:px-8 md:px-10">
      <div class="max-w-2xl mx-auto bg-white dark:bg-gray-900 shadow-xl rounded-xl p-8 transition duration-300">
        <h1 class="text-3xl font-bold mb-6 text-center flex items-center justify-center gap-3 text-yellow-600 dark:text-yellow-400">
          <i data-feather="edit-3" class="w-7 h-7"></i>
          Edit Ruangan
        </h1>

        <?php if ($error): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 animate-pulse">
            <?= $error ?>
          </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
          <div>
            <label for="name" class="block mb-1 font-medium">Nama Ruangan</label>
            <input 
              type="text" 
              id="name" 
              name="name" 
              value="<?= htmlspecialchars($room['name']) ?>" 
              required 
              class="w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 transition"
              placeholder="Contoh: Ruang Rapat Kadis"
            >
          </div>

          <div class="flex justify-between items-center">
            <a href="ruangan.php" class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-md transition">
              <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali
            </a>

            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-yellow-500 hover:bg-yellow-600 rounded-md transition">
              <i data-feather="save" class="w-5 h-5"></i> Simpan Perubahan
            </button>
          </div>
        </form>
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
