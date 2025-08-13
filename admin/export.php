<?php
require_once '../inc/db.php'; // koneksi PDO

$type = $_GET['type'] ?? 'csv';

// Ambil data
$query = "SELECT b.id, b.name, b.dinas, b.bidang, r.name AS room_name, 
                 b.date, b.start_time, b.end_time, b.status
          FROM bookings b
          JOIN rooms r ON b.room_id = r.id
          ORDER BY b.date DESC, b.start_time ASC";
$stmt = $pdo->query($query);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    die("Tidak ada data untuk diexport.");
}

if ($type === 'csv') {
    // Export CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=peminjaman.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array_keys($rows[0])); // header kolom
    foreach ($rows as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;

} elseif ($type === 'excel') {
    // Export Excel (format .xls sederhana)
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=peminjaman.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<table border='1'>";
    echo "<tr>";
    foreach (array_keys($rows[0]) as $col) {
        echo "<th>" . htmlspecialchars($col) . "</th>";
    }
    echo "</tr>";

    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $val) {
            echo "<td>" . htmlspecialchars($val) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    exit;
} else {
    die("Format export tidak dikenali.");
}
