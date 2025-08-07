<?php
session_start();
require_once '../inc/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

include '../inc/header.php';
?>

<div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
  <?php include 'components/navbar.php'; ?>
  
  <div class="flex flex-1">
    <?php include 'components/sidebar.php'; ?>

    <main class="flex-1 p-6 overflow-x-auto">
      <h1 class="text-3xl font-bold mb-6 flex items-center gap-2">
        <i data-feather="clipboard" class="w-6 h-6 text-blue-500"></i>
        Daftar Peminjaman Ruangan
      </h1>

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

      <div class="overflow-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl ring-1 ring-gray-200 dark:ring-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-100 dark:bg-gray-700">
            <tr class="text-sm font-semibold text-gray-700 dark:text-gray-100 uppercase tracking-wider">
              <th class="px-5 py-3 text-left">#</th>
              <th class="px-5 py-3 text-left">Pemohon</th>
              <th class="px-5 py-3 text-left">Ruangan</th>
              <th class="px-5 py-3 text-left">Tanggal</th>
              <th class="px-5 py-3 text-left">Waktu</th>
              <th class="px-5 py-3 text-left">Status</th>
              <th class="px-5 py-3 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <?php
            $no = 1;
            foreach ($rows as $row):
              $status = $row['status'];
              $badge = match ($status) {
                'approved' => 'bg-green-100 text-green-700 border border-green-500',
                'rejected' => 'bg-red-100 text-red-700 border border-red-500',
                default => 'bg-yellow-100 text-yellow-800 border border-yellow-400'
              };
            ?>
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <td class="px-5 py-3 font-medium"><?= $no++ ?></td>
                <td class="px-5 py-3">
                  <?= htmlspecialchars($row['name']) ?><br>
                  <span class="text-xs text-gray-500"><?= htmlspecialchars($row['dinas']) ?> - <?= htmlspecialchars($row['bidang']) ?></span>
                </td>
                <td class="px-5 py-3"><?= htmlspecialchars($row['room_name']) ?></td>
                <td class="px-5 py-3"><?= date('d M Y', strtotime($row['date'])) ?></td>
                <td class="px-5 py-3"><?= $row['start_time'] ?> - <?= $row['end_time'] ?></td>
                <td class="px-5 py-3">
                  <span class="text-xs font-semibold px-2 py-1 rounded-full inline-block <?= $badge ?>">
                    <?= ucfirst($status) ?>
                  </span>
                </td>
                <td class="px-5 py-3">
                  <div class="flex gap-2">
                    <?php if ($status === 'pending') : ?>
                      <form action="approve.php" method="post" class="inline">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" title="Setujui" class="bg-green-600 hover:bg-green-700 text-white p-1 rounded-full transition">
                          <i data-feather="check" class="w-4 h-4"></i>
                        </button>
                      </form>
                      <form action="approve.php" method="post" class="inline">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" title="Tolak" class="bg-red-600 hover:bg-red-700 text-white p-1 rounded-full transition">
                          <i data-feather="x" class="w-4 h-4"></i>
                        </button>
                      </form>
                    <?php endif; ?>

                    <!-- Tombol Lihat Detail -->
                    <a href="detail.php?id=<?= $row['id'] ?>" title="Lihat Detail"
                      class="bg-blue-600 hover:bg-blue-700 text-white p-1 rounded-full transition">
                      <i data-feather="info" class="w-4 h-4"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (count($rows) === 0): ?>
              <tr>
                <td colspan="7" class="text-center px-5 py-4 text-gray-500 italic">Belum ada peminjaman.</td>
              </tr>
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

<?php include '../inc/footer.php'; ?>
