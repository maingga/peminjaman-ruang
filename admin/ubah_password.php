<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$adminId = $_SESSION['admin']['id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPass = $_POST['current_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    // Ambil data admin dari db
    $stmt = $pdo->prepare("SELECT password FROM admins WHERE id = ?");
    $stmt->execute([$adminId]);
    $admin = $stmt->fetch();

    // Cek password lama
    if (!password_verify($currentPass, $admin['password'])) {
        $errors[] = "Password lama salah.";
    }

    // Validasi password baru
    if ($newPass !== $confirmPass) {
        $errors[] = "Konfirmasi password tidak cocok.";
    } elseif (strlen($newPass) < 6) {
        $errors[] = "Password baru minimal 6 karakter.";
    }

    // Jika validasi lolos, update password
    if (empty($errors)) {
        $newPassHash = password_hash($newPass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
        $stmt->execute([$newPassHash, $adminId]);

        $success = "Password berhasil diubah.";
    }
}
?>

<?php include '../inc/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 transition duration-300 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-900 rounded-xl shadow-xl p-10">
    <h2 class="mt-6 text-center text-3xl font-extrabold tracking-tight">
      Ubah Password
    </h2>

    <?php if ($success): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <strong class="font-bold">Sukses! </strong>
        <span class="block sm:inline"><?= htmlspecialchars($success) ?></span>
        <button @click="show = false" class="absolute top-2 right-2 text-green-700 hover:text-green-900 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)">
        <ul class="list-disc list-inside">
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
        <button @click="show = false" class="absolute top-2 right-2 text-red-700 hover:text-red-900 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    <?php endif; ?>

    <form class="mt-8 space-y-6" action="" method="POST" novalidate>
      <div class="rounded-md shadow-sm space-y-5">
        <div>
          <label for="current_password" class="block text-sm font-medium mb-1">Password Lama</label>
          <input id="current_password" name="current_password" type="password" required autofocus
            placeholder="Masukkan password lama"
            class="appearance-none rounded-md relative block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 transition duration-200" />
        </div>
        <div>
          <label for="new_password" class="block text-sm font-medium mb-1">Password Baru</label>
          <input id="new_password" name="new_password" type="password" required minlength="6"
            placeholder="Minimal 6 karakter"
            class="appearance-none rounded-md relative block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 transition duration-200" />
        </div>
        <div>
          <label for="confirm_password" class="block text-sm font-medium mb-1">Konfirmasi Password Baru</label>
          <input id="confirm_password" name="confirm_password" type="password" required minlength="6"
            placeholder="Ketik ulang password baru"
            class="appearance-none rounded-md relative block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 transition duration-200" />
        </div>
      </div>

      <div class="flex items-center justify-between">
        <a href="dashboard.php" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition duration-200">
          Batal
        </a>
        <button type="submit"
          class="group relative flex justify-center py-2 px-8 border border-transparent text-sm font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300">
          Ubah Password
        </button>
      </div>
    </form>
  </div>
</div>

<?php include '../inc/footer.php'; ?>
