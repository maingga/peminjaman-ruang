<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);

  if ($name === '') {
    $error = 'Nama ruangan tidak boleh kosong.';
  } else {
    $stmt = $pdo->prepare("INSERT INTO rooms (name) VALUES (:name)");
    $stmt->execute(['name' => $name]);
    header('Location: ruangan.php?success=1');
    exit;
  }
}

include '../inc/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-blue-200 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition duration-300">
  <?php include 'components/navbar.php'; ?>

  <div class="flex">
    <?php include 'components/sidebar.php'; ?>

    <main class="flex-1 p-6">
      <div class="max-w-2xl mx-auto bg-white dark:bg-gray-900 rounded-xl shadow-xl p-8 transition-all duration-500">
        <h1 class="text-3xl font-bold mb-6 flex items-center gap-3 text-blue-700 dark:text-blue-400">
          <i data-feather="plus-square" class="w-7 h-7"></i>
          Tambah Ruangan Baru
        </h1>

        <?php if ($error): ?>
          <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 transition-all">
            <?= $error ?>
          </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
          <div>
            <label for="name" class="block text-sm font-medium mb-1">Nama Ruangan <span class="text-red-500">*</span></label>
            <input
              type="text"
              id="name"
              name="name"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
              placeholder="Contoh: Hall Utama, Ruang Rapat Kadis"
            >
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
            <a href="ruangan.php" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md transition">
              Batal
            </a>
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition">
              Simpan
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>

<script>
  feather.replace();

  // Optional real-time validation
  const nameInput = document.getElementById('name');
  nameInput.addEventListener('input', () => {
    nameInput.classList.remove('border-red-500');
    if (nameInput.value.trim() === '') {
      nameInput.classList.add('border-red-500');
    }
  });
</script>

<?php include '../inc/footer.php'; ?>
