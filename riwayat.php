<?php
require_once 'inc/db.php';

$bookings = [];
$phone = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);

    // Query dengan join ke tabel rooms untuk menampilkan nama ruangan
    $stmt = $pdo->prepare("
        SELECT b.*, r.name AS room_name 
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE b.phone = :phone
        ORDER BY b.date DESC, b.start_time DESC
    ");
    $stmt->execute(['phone' => $phone]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/navbar.php'; ?>

<section class="min-h-screen pt-24 px-6 py-12 bg-gradient-to-br from-blue-50 to-white dark:from-gray-900 dark:to-gray-800 transition-all duration-300">
  <div class="max-w-5xl mx-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-3xl shadow-xl p-10 backdrop-blur-sm">
    <h1 class="text-4xl font-extrabold text-center mb-10 text-blue-900 dark:text-yellow-400 tracking-tight">
      📜 Cek Riwayat Peminjaman
    </h1>

    <form method="POST" class="flex flex-col sm:flex-row items-center gap-5 mb-12">
      <input 
        type="text" 
        name="phone" 
        value="<?= htmlspecialchars($phone) ?>"
        placeholder="Masukkan nomor HP (cth: 08123456789)"
        required
        autocomplete="off"
        class="flex-grow px-6 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-500 transition"
      />
      <button type="submit" 
        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-semibold rounded-xl shadow-lg flex items-center gap-3 transition-all duration-300 select-none"
      >
        🔍 <span>Cek Riwayat</span>
      </button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
      <?php if (count($bookings) > 0): ?>
        <div class="flex flex-col divide-y divide-gray-200 dark:divide-gray-700">
          <?php 
            $statusClass = [
              'pending'   => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
              'approved'  => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
              'rejected'  => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
              'cancelled' => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
            ];
            $statusText = [
              'pending'   => 'Menunggu',
              'approved'  => 'Disetujui',
              'rejected'  => 'Ditolak',
              'cancelled' => 'Dibatalkan'
            ];
          ?>
          <?php foreach ($bookings as $b): ?>
            <div class="py-5 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 flex-grow text-gray-700 dark:text-gray-300">
                <div>
                  <div class="text-sm font-semibold text-blue-900 dark:text-yellow-400">Nama</div>
                  <div class="mt-1 text-base font-medium"><?= htmlspecialchars($b['name']) ?></div>
                </div>
                <div>
                  <div class="text-sm font-semibold">Ruangan</div>
                  <div class="mt-1"><?= htmlspecialchars($b['room_name']) ?></div>
                </div>
                <div>
                  <div class="text-sm font-semibold">Tanggal</div>
                  <div class="mt-1"><?= date("d M Y", strtotime($b['date'])) ?></div>
                </div>
                <div>
                  <div class="text-sm font-semibold">Jam</div>
                  <div class="mt-1"><?= substr($b['start_time'],0,5) ?> - <?= substr($b['end_time'],0,5) ?></div>
                </div>
              </div>

              <div class="flex flex-col sm:flex-row sm:items-center sm:gap-8 mt-3 md:mt-0">
                <span class="px-4 py-1 rounded-full text-sm font-semibold <?= $statusClass[$b['status']] ?? '' ?>">
                  <?= $statusText[$b['status']] ?? htmlspecialchars($b['status']) ?>
                </span>

                <?php if ($b['status'] === 'pending'): ?>
                  <a href="batalkan.php?token=<?= urlencode($b['cancel_token']) ?>" 
                    class="text-red-600 hover:text-red-800 font-semibold transition select-none"
                    onclick="return confirm('Yakin ingin membatalkan peminjaman ini?')"
                  >
                    ❌ Batalkan
                  </a>
                <?php else: ?>
                  <span class="text-gray-400 select-none">-</span>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-center text-red-600 dark:text-red-400 font-medium mt-10 text-lg">
          ❗ Tidak ada data peminjaman ditemukan untuk nomor tersebut.
        </p>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<?php include 'inc/footer.php'; ?>

<!-- JS -->
<script src="assets/js/navbar.js"></script>
