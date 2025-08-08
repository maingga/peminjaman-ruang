<?php
require_once 'inc/db.php';

$bookings = [];
$phone = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);

    // Join ke tabel rooms untuk menampilkan nama ruangan
    $stmt = $conn->prepare("
        SELECT b.*, r.name AS room_name 
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        WHERE b.phone = ?
        ORDER BY b.date DESC, b.start_time DESC
    ");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<?php include 'inc/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-200 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-100 p-6">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">📜 Cek Riwayat Peminjaman</h1>

        <form method="POST" class="flex flex-col sm:flex-row items-center gap-3 mb-6">
            <div class="flex items-center w-full sm:w-auto gap-2">
                <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>"
                    class="px-4 py-2 rounded-lg border w-full sm:w-64 shadow-sm focus:ring-2 focus:ring-blue-500 transition"
                    placeholder="Masukkan nomor HP" required>
            </div>
            <button type="submit"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md flex items-center gap-2 transition">
                🔍 <span>Cek Riwayat</span>
            </button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <?php if (count($bookings) > 0): ?>
                <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left">Nama</th>
                                <th class="px-4 py-3 text-left">Ruangan</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Jam</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($bookings as $b): ?>
                                <tr class="hover:bg-blue-50 dark:hover:bg-gray-800 transition">
                                    <td class="px-4 py-3"><?= htmlspecialchars($b['name']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($b['room_name']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars(date("d M Y", strtotime($b['date']))) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars(substr($b['start_time'],0,5)) ?> - <?= htmlspecialchars(substr($b['end_time'],0,5)) ?></td>
                                    <td class="px-4 py-3">
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
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusClass[$b['status']] ?? '' ?>">
                                            <?= $statusText[$b['status']] ?? htmlspecialchars($b['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <?php if ($b['status'] === 'pending'): ?>
                                            <a href="batalkan.php?token=<?= urlencode($b['cancel_token']) ?>"
                                               class="text-red-600 hover:text-red-800 font-medium transition"
                                               onclick="return confirm('Yakin ingin membatalkan peminjaman ini?')">
                                               ❌ Batalkan
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-red-500 text-center mt-4 font-medium">❗ Tidak ada data peminjaman ditemukan.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'inc/footer.php'; ?>
