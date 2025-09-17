<?php
require_once '../inc/functions.php';
require_once '../inc/db.php';
require_once '../inc/auth.php';

// Ambil setting hero
$stmt = $pdo->prepare("SELECT value_text FROM settings WHERE key_name = 'hero_background'");
$stmt->execute();
$hero = $stmt->fetchColumn() ?: 'assets/images/ruang-rapat.jpeg';

// Proses upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploaded_file = upload_and_resize_image('hero_img', '../assets/images/', 1200, 700);

    if ($uploaded_file) {
        $stmt = $pdo->prepare("UPDATE settings SET value_text = :img WHERE key_name = 'hero_background'");
        $stmt->execute(['img' => 'assets/images/' . $uploaded_file]);
        $success = "Background hero berhasil diperbarui!";
        $hero = 'assets/images/' . $uploaded_file;
    } else {
        $error = "Gagal upload gambar! Pastikan file gambar valid, ukuran < 5MB, dan tipe jpg/png/gif.";
    }
}
?>

<?php include '../inc/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-300">
  <?php include 'components/navbar.php'; ?>
  <div class="flex flex-1">
    <?php include 'components/sidebar.php'; ?>

                        <!-- Overlay untuk mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity duration-300 pointer-events-none"></div>


    <main class="flex-1 p-6 max-w-6xl mx-auto animate-fadeInUp">
      <!-- Breadcrumb -->
      <nav class="text-blue-700 dark:text-blue-400 text-sm mb-6" aria-label="Breadcrumb">
        <ol class="list-reset flex">
          <li><a href="dashboard.php" class="hover:text-blue-800 dark:hover:text-blue-300 transition-colors">Dashboard</a></li>
          <li><span class="mx-2">/</span></li>
          <li class="font-semibold">Edit Hero Background</li>
        </ol>
      </nav>

      <!-- Card -->
      <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
        <h1 class="text-2xl font-bold text-blue-700 dark:text-blue-400 mb-4 flex items-center gap-2">
          <i data-feather="image" class="w-6 h-6"></i> Edit Hero Background
        </h1>

        <!-- Alert -->
        <?php if (isset($success)): ?>
          <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 border-l-4 border-green-500 shadow-sm flex items-center justify-between">
            <span><?= htmlspecialchars($success) ?></span>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 dark:text-green-300 dark:hover:text-green-100 transition duration-200">
              <i data-feather="x" class="w-4 h-4"></i>
            </button>
          </div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
          <div class="mb-4 px-4 py-2 rounded bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 border-l-4 border-red-500 shadow-sm flex items-center justify-between">
            <span><?= htmlspecialchars($error) ?></span>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 dark:text-red-300 dark:hover:text-red-100 transition duration-200">
              <i data-feather="x" class="w-4 h-4"></i>
            </button>
          </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="space-y-6">
          <div>
            <label class="block font-semibold mb-2 text-blue-700 dark:text-blue-400">Upload Background Baru</label>
            <input type="file" name="hero_img" accept="image/*" required
              class="block w-full text-gray-700 dark:text-gray-200 file:border-0 file:bg-blue-600 file:text-white file:rounded-lg file:px-4 file:py-2 hover:file:bg-blue-700 transition-colors duration-200">
          </div>

          <div>
            <label class="block font-semibold mb-2 text-blue-700 dark:text-blue-400">Preview Saat Ini</label>
            <div class="w-full h-64 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-inner">
              <img id="preview" src="../<?= htmlspecialchars($hero) ?>" alt="Preview Hero"
                class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
            </div>
          </div>

          <button type="submit"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 active:bg-blue-800 shadow-md transition-all duration-200 flex items-center gap-2">
            <i data-feather="save" class="w-5 h-5"></i> Simpan Perubahan
          </button>
        </form>
      </div>
    </main>
  </div>
</div>

<script>
  feather.replace();

  // Preview gambar saat upload
  const fileInput = document.querySelector('input[name="hero_img"]');
  const preview = document.getElementById('preview');

  fileInput.addEventListener('change', e => {
    const file = e.target.files[0];
    if(file){
      const reader = new FileReader();
      reader.onload = evt => {
        preview.src = evt.target.result;
      }
      reader.readAsDataURL(file);
    }
  });
</script>

<!-- Script interaksi dashboard -->
<script src="../assets/js/dashboard.js"></script>


<?php include '../inc/footer.php'; ?>
