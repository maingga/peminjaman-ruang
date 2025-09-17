<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

include '../inc/header.php';
?>

<div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-900 dark:to-gray-800 text-gray-900 dark:text-gray-100 transition-colors duration-500">
  <?php include 'components/navbar.php'; ?>
  
  <div class="flex flex-1 overflow-hidden">
    <?php include 'components/sidebar.php'; ?>

        <!-- Overlay untuk mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden transition-opacity duration-300 pointer-events-none"></div>


    <main class="flex-1 p-6 md:p-10 overflow-auto max-w-full">
      <h1 class="text-3xl font-extrabold mb-8 flex items-center gap-3 select-none text-blue-700 dark:text-blue-400">
        <i data-feather="clipboard" class="w-7 h-7"></i>
        Daftar Peminjaman Ruangan
      </h1>

          <!-- Tombol Export -->
          <div class="mb-6 flex flex-wrap gap-3">
            <a href="export.php?type=csv" 
              class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-semibold shadow-md transition-transform transform hover:scale-105 active:scale-95">
              <i data-feather="file-text" class="w-4 h-4"></i>
              CSV
            </a>

            <a href="export.php?type=excel" 
              class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-semibold shadow-md transition-transform transform hover:scale-105 active:scale-95">
              <i data-feather="grid" class="w-4 h-4"></i>
              Excel
            </a>
          </div>
          <!-- End Tombol Export -->

      <?php
      $query = "SELECT b.*, r.name AS room_name 
                FROM bookings b
                JOIN rooms r ON b.room_id = r.id
                ORDER BY b.date DESC, b.start_time ASC";

      try {
        $stmt = $pdo->query($query);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        die("Gagal mengambil data: " . $e->getMessage());
      }
      ?>

      <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-xl shadow-lg ring-1 ring-gray-300 dark:ring-gray-700 max-w-full mx-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
          <thead class="bg-gray-100 dark:bg-gray-800">
            <tr class="text-sm font-semibold text-gray-700 dark:text-gray-100 uppercase tracking-wide select-none">
              <th class="px-4 py-3 text-left w-12">#</th>
              <th class="px-4 py-3 text-left max-w-[150px] truncate">Pemohon</th>
              <th class="px-4 py-3 text-left max-w-[140px] truncate">Ruangan</th>
              <th class="px-4 py-3 text-left whitespace-nowrap">Tanggal</th>
              <th class="px-4 py-3 text-left whitespace-nowrap">Waktu</th>
              <th class="px-4 py-3 text-left w-24">Status</th>
              <th class="px-4 py-3 text-center w-28">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <?php if (count($rows) === 0): ?>
              <tr>
                <td colspan="7" class="text-center px-4 py-6 text-gray-500 italic select-none">Belum ada peminjaman.</td>
              </tr>
            <?php else: ?>
              <?php
              $no = 1;
              foreach ($rows as $row):
                $status = $row['status'];
                $badge = match ($status) {
                  'approved' => 'bg-green-100 text-green-800 border border-green-500',
                  'rejected' => 'bg-red-100 text-red-800 border border-red-500',
                  default => 'bg-yellow-100 text-yellow-800 border border-yellow-400'
                };
              ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                  <td class="px-4 py-3 font-medium whitespace-nowrap"><?= $no++ ?></td>
                  <td class="px-4 py-3 max-w-[150px] truncate" title="<?= htmlspecialchars($row['name']) ?>">
                    <?= htmlspecialchars($row['name']) ?><br>
                    <span class="text-xs text-gray-500 dark:text-gray-400 select-text"><?= htmlspecialchars($row['dinas']) ?> - <?= htmlspecialchars($row['bidang']) ?></span>
                  </td>
                  <td class="px-4 py-3 max-w-[140px] truncate" title="<?= htmlspecialchars($row['room_name']) ?>">
                    <?= htmlspecialchars($row['room_name']) ?>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap"><?= date('d M Y', strtotime($row['date'])) ?></td>
                  <td class="px-4 py-3 whitespace-nowrap"><?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full <?= $badge ?> select-none">
                      <?= ucfirst($status) ?>
                    </span>
                  </td>
                  <td class="px-4 py-3 text-center whitespace-nowrap">
                    <div class="flex justify-center gap-2">
                      <?php if ($status === 'pending'): ?>
                        <form action="approve.php" method="post" class="inline">
                          <input type="hidden" name="id" value="<?= $row['id'] ?>">
                          <input type="hidden" name="action" value="approve">
                          <button type="submit" aria-label="Setujui peminjaman" title="Setujui" class="bg-green-600 hover:bg-green-700 active:bg-green-800 text-white p-2 rounded-full transition shadow-md focus:outline-none focus:ring-2 focus:ring-green-400">
                            <i data-feather="check" class="w-4 h-4"></i>
                          </button>
                        </form>
                        <form action="approve.php" method="post" class="inline">
                          <input type="hidden" name="id" value="<?= $row['id'] ?>">
                          <input type="hidden" name="action" value="reject">
                          <button type="submit" aria-label="Tolak peminjaman" title="Tolak" class="bg-red-600 hover:bg-red-700 active:bg-red-800 text-white p-2 rounded-full transition shadow-md focus:outline-none focus:ring-2 focus:ring-red-400">
                            <i data-feather="x" class="w-4 h-4"></i>
                          </button>
                        </form>
                      <?php endif; ?>

                      <a href="detail.php?id=<?= $row['id'] ?>" aria-label="Lihat detail peminjaman" title="Lihat Detail"
                         class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white p-2 rounded-full transition shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <i data-feather="info" class="w-4 h-4"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
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
