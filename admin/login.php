<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

include '../inc/header.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-200 dark:from-gray-900 dark:to-gray-800 px-4 py-12">
  <div class="w-full max-w-md backdrop-blur-sm bg-white/90 dark:bg-gray-800/90 rounded-2xl shadow-xl p-8 space-y-6 animate-fadeInUp transition-transform hover:scale-[1.01] duration-300">
    
    <div class="text-center">
      <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white mb-1">🔐 Admin Login</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400">Silakan masuk untuk mengakses Dashboard</p>
    </div>

    <?php if (!empty($error)) : ?>
      <div class="bg-red-100 text-red-700 border border-red-400 px-4 py-3 rounded-lg">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="process_login.php" class="space-y-5" onsubmit="return handleSubmit()">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
      <div>
        <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">Email</label>
        <input type="email" name="email" required placeholder="email@admin.com"
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" />
      </div>

      <div>
        <label class="block mb-1 text-gray-700 dark:text-gray-300 font-medium">Password</label>
        <div class="relative">
          <input type="password" name="password" id="password" required placeholder="••••••••"
            class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" />
          <button type="button" onclick="togglePassword()" class="absolute right-3 top-2.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
            👁️
          </button>
        </div>
      </div>

      <div>
        <button id="loginBtn" type="submit"
          class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-300 ease-in-out shadow-md flex items-center justify-center gap-2">
          <span id="btnText">Masuk Sekarang</span>
          <svg id="spinner" class="w-5 h-5 hidden animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
          </svg>
        </button>
      </div>
    </form>

    <div class="text-center text-sm text-gray-600 dark:text-gray-400">
      <a href="../index.php" class="hover:underline hover:text-blue-600 dark:hover:text-blue-400 transition">← Kembali ke Halaman Utama</a>
    </div>
  </div>
</div>

<?php include '../inc/footer.php'; ?>
