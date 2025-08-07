<?php
require_once 'inc/db.php';

// Ambil semua data booking terbaru
$stmt = $pdo->query("
    SELECT b.*, r.name AS room_name 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    ORDER BY b.date DESC, b.start_time ASC
");
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Peminjaman Ruangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Riwayat Peminjaman Ruangan</h2>
    <p>Semua data peminjaman (baik disetujui, ditolak, maupun menunggu persetujuan).</p>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Ruangan</th>
                <th>Nama Peminjam</th>
                <th>Dinas</th>
                <th>Bidang</th>
                <th>Jam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($bookings) === 0): ?>
                <tr>
                    <td colspan="7" class="text-center">Belum ada data peminjaman.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($bookings as $b): ?>
                    <tr class="<?= $b['status'] === 'approved' ? 'table-success' : ($b['status'] === 'rejected' ? 'table-danger' : '') ?>">
                        <td><?= $b['date'] ?></td>
                        <td><?= $b['room_name'] ?></td>
                        <td><?= htmlspecialchars($b['name']) ?></td>
                        <td><?= htmlspecialchars($b['dinas']) ?></td>
                        <td><?= htmlspecialchars($b['bidang']) ?></td>
                        <td><?= substr($b['start_time'], 0, 5) ?> - <?= substr($b['end_time'], 0, 5) ?></td>
                        <td>
                            <?php
                            if ($b['status'] === 'approved') {
                                echo "<span class='badge bg-success'>Disetujui</span>";
                            } elseif ($b['status'] === 'pending') {
                                echo "<span class='badge bg-warning text-dark'>Menunggu</span>";
                            } else {
                                echo "<span class='badge bg-danger'>Ditolak</span>";
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
