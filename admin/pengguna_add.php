<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $pass]);

    $_SESSION['flash_success'] = "Admin berhasil ditambahkan.";
    header('Location: pengguna.php');
    exit;
}

include '../inc/header.php';
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition duration-300">
  <?php include 'components/navbar.php'; ?>

  <div class="flex">
    <?php include 'components/sidebar.php'; ?>

    <main class="flex-1 p-6 mt-6 md:mt-0 max-w-xl mx-auto">
      <h1 class="text-3xl font-bold mb-8 flex items-center gap-3">
        <i data-feather="user-plus" class="w-8 h-8 text-blue-600 dark:text-blue-400"></i>
        Tambah Admin
      </h1>

      <form method="post" class="space-y-7 bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-xl ring-1 ring-gray-200 dark:ring-gray-700">
        <div>
          <label for="name" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Nama Lengkap</label>
          <input id="name" type="text" name="name" required
            placeholder="Masukkan nama lengkap"
            class="w-full px-5 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                   focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-600 transition duration-300" />
        </div>

        <div>
          <label for="email" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Email</label>
          <input id="email" type="email" name="email" required
            placeholder="contoh@domain.com"
            class="w-full px-5 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                   focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-600 transition duration-300" />
        </div>

        <div>
          <label for="password" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Password</label>
          <input id="password" type="password" name="password" required minlength="6"
            placeholder="Minimal 6 karakter"
            class="w-full px-5 py-3 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                   focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-600 transition duration-300" />
        </div>

        <div class="flex gap-4 justify-start">
          <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-500 text-white text-lg font-semibold rounded-lg shadow-lg transition duration-300">
            <i data-feather="check" class="w-5 h-5"></i> Simpan
          </button>

          <a href="pengguna.php"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg shadow-lg transition duration-300 font-semibold text-lg">
            <i data-feather="x" class="w-5 h-5"></i> Batal
          </a>
        </div>
      </form>
    </main>
  </div>
</div>

<script>
  feather.replace();
</script>

<?php include '../inc/footer.php'; ?>
