<?php
require_once 'inc/db.php';
session_start();

$errors = [];
$success = '';

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim(htmlspecialchars($_POST['name']));
  $dinas = trim(htmlspecialchars($_POST['dinas']));
  $bidang = trim(htmlspecialchars($_POST['bidang']));
  $phone = trim(htmlspecialchars($_POST['phone']));
  $room_id = $_POST['room_id'];
  $date = $_POST['date'];
  $start_time = $_POST['start_time'];
  $end_time = $_POST['end_time'];
  $csrf_token = $_POST['csrf_token'];

  if ($csrf_token !== $_SESSION['csrf_token']) {
    $errors[] = "Token tidak valid.";
  }

  if (!$name || !$dinas || !$bidang || !$phone || !$room_id || !$date || !$start_time || !$end_time) {
    $errors[] = "Semua kolom wajib diisi.";
  }

  if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
    $errors[] = "Nomor HP tidak valid.";
  }

  if ($start_time >= $end_time) {
    $errors[] = "Jam selesai harus lebih besar dari jam mulai.";
  }

  if (empty($errors)) {
    $stmt = $pdo->prepare("
      SELECT * FROM bookings 
      WHERE room_id = ? AND date = ? 
      AND start_time < ? AND end_time > ? 
      AND status != 'rejected'
    ");
    $stmt->execute([$room_id, $date, $end_time, $start_time]);
    $conflict = $stmt->fetch();

    if ($conflict) {
      $errors[] = "Jadwal bentrok dengan peminjaman lain.";
    } else {
      $stmt = $pdo->prepare("
        INSERT INTO bookings 
        (name, dinas, bidang, phone, room_id, date, start_time, end_time, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
      ");
      $stmt->execute([$name, $dinas, $bidang, $phone, $room_id, $date, $start_time, $end_time]);
      $success = "✅ Peminjaman berhasil diajukan.";
    }
  }
}

$rooms = $pdo->query("SELECT * FROM rooms")->fetchAll();
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/navbar.php'; ?>

<section class="min-h-screen pt-20 md:pt-24 flex items-center justify-center px-4 py-10 bg-gradient-to-br from-blue-50 to-white dark:from-gray-950 dark:to-gray-900 transition-all duration-300" data-aos="fade-up">
  <div class="backdrop-blur-xl bg-white/90 dark:bg-gray-900/70 border border-gray-200 dark:border-gray-700 shadow-2xl rounded-3xl w-full max-w-3xl p-8 md:p-10 transition-all duration-300">
    <h2 class="text-3xl font-extrabold text-center text-blue-900 dark:text-yellow-400 mb-8 tracking-tight">📋 Form Peminjaman Ruang Rapat</h2>

    <?php if (!empty($errors)): ?>
      <div class="bg-red-100 dark:bg-red-200/10 text-red-800 dark:text-red-400 border-l-4 border-red-500 p-4 mb-6 rounded">
        <ul class="list-disc pl-6">
          <?php foreach ($errors as $e) echo "<li>$e</li>"; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="bg-green-100 dark:bg-green-200/10 text-green-800 dark:text-green-400 border-l-4 border-green-500 p-4 mb-6 rounded">
        <?= $success ?>
      </div>
    <?php endif; ?>

    <form method="POST" onsubmit="return confirm('Ajukan peminjaman sekarang?')">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php
        $fields = [
          ['label' => '👤 Nama Peminjam', 'name' => 'name', 'type' => 'text'],
          ['label' => '📱 Nomor HP', 'name' => 'phone', 'type' => 'text'],
          ['label' => '🏢 Dinas', 'name' => 'dinas', 'type' => 'text'],
          ['label' => '📂 Bidang', 'name' => 'bidang', 'type' => 'text'],
        ];
        foreach ($fields as $f): ?>
        <div>
          <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-200"><?= $f['label'] ?></label>
          <input type="<?= $f['type'] ?>" name="<?= $f['name'] ?>" required value="<?= $_POST[$f['name']] ?? '' ?>"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-colors duration-300">
        </div>
        <?php endforeach; ?>

        <div class="md:col-span-2">
          <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-200">🏛️ Ruang</label>
          <select name="room_id" required
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-colors duration-300">
            <option value="">-- Pilih Ruang --</option>
            <?php foreach ($rooms as $room): ?>
              <option value="<?= $room['id'] ?>" <?= (isset($_POST['room_id']) && $_POST['room_id'] == $room['id']) ? 'selected' : '' ?>>
                <?= $room['name'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-200">📅 Tanggal</label>
          <input type="date" name="date" required value="<?= $_POST['date'] ?? '' ?>"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-colors duration-300">
        </div>

        <div>
          <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-200">🕘 Jam Mulai</label>
          <input type="time" name="start_time" required value="<?= $_POST['start_time'] ?? '' ?>"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-colors duration-300">
        </div>

        <div>
          <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-200">🕓 Jam Selesai</label>
          <input type="time" name="end_time" required value="<?= $_POST['end_time'] ?? '' ?>"
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-colors duration-300">
        </div>
      </div>

      <div class="mt-8">
        <button type="submit" id="submitBtn"
          class="w-full py-3 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-semibold rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300">
          🚀 Ajukan Sekarang
        </button>
      </div>

      <div class="mt-4 text-center">
        <a href="index.php" class="text-sm text-gray-600 dark:text-gray-400 hover:underline transition">← Kembali ke Beranda</a>
      </div>
    </form>
  </div>
</section>

<?php include 'inc/footer.php'; ?>
