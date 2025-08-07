<?php
require_once 'inc/db.php';
$rooms = $pdo->query("SELECT * FROM rooms")->fetchAll();
$today = date('Y-m-d');
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/navbar.php'; ?>

<section class="py-20 bg-[#E3F2FD] dark:bg-gray-900 min-h-screen">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-3xl font-bold text-center text-[#0D47A1] dark:text-white mb-10" data-aos="fade-down">
      Live Jadwal Peminjaman - <?= date('d M Y') ?>
    </h2>

    <?php foreach ($rooms as $room): ?>
      <div class="mb-10" data-aos="fade-up" data-aos-delay="100">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden">
          <div class="bg-[#0D47A1] text-white px-6 py-4">
            <h3 class="text-xl font-semibold"><?= htmlspecialchars($room['name']) ?></h3>
          </div>
          <div class="p-6 overflow-x-auto">
            <table class="min-w-full table-auto text-sm md:text-base">
              <thead class="bg-gray-100 dark:bg-gray-700 text-[#0D47A1] dark:text-white">
                <tr>
                  <th class="px-4 py-2 text-left">Nama Peminjam</th>
                  <th class="px-4 py-2 text-left">Dinas</th>
                  <th class="px-4 py-2 text-left">Bidang</th>
                  <th class="px-4 py-2 text-left">Jam</th>
                  <th class="px-4 py-2 text-left">Status</th>
                </tr>
              </thead>
              <tbody class="text-gray-700 dark:text-gray-300">
                <?php
                $stmt = $pdo->prepare("SELECT * FROM bookings WHERE room_id = ? AND date = ? ORDER BY start_time ASC");
                $stmt->execute([$room['id'], $today]);
                $bookings = $stmt->fetchAll();

                if (count($bookings) === 0): ?>
                  <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada peminjaman hari ini.</td>
                  </tr>
                <?php else:
                  foreach ($bookings as $booking): ?>
                    <tr class="<?= ($booking['status'] === 'approved') ? 'bg-green-50 dark:bg-green-900/30' : (($booking['status'] === 'rejected') ? 'bg-red-50 dark:bg-red-900/30' : '') ?>">
                      <td class="px-4 py-2"><?= htmlspecialchars($booking['name']) ?></td>
                      <td class="px-4 py-2"><?= htmlspecialchars($booking['dinas']) ?></td>
                      <td class="px-4 py-2"><?= htmlspecialchars($booking['bidang']) ?></td>
                      <td class="px-4 py-2"><?= substr($booking['start_time'], 0, 5) ?> - <?= substr($booking['end_time'], 0, 5) ?></td>
                      <td class="px-4 py-2">
                        <?php
                          if ($booking['status'] === 'approved') {
                              echo "<span class='inline-block bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-full'>Disetujui</span>";
                          } elseif ($booking['status'] === 'pending') {
                              echo "<span class='inline-block bg-yellow-400 text-black text-xs font-semibold px-3 py-1 rounded-full'>Menunggu</span>";
                          } else {
                              echo "<span class='inline-block bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full'>Ditolak</span>";
                          }
                        ?>
                      </td>
                    </tr>
                <?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include 'inc/footer.php'; ?>

<!-- Tambahkan JS jika belum -->
<script src="assets/js/navbar.js"></script>
