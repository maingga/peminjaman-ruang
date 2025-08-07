<?php
session_start();
require_once '../inc/db.php';

// Cek apakah admin login
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}

// Ambil ID ruangan dari query string
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
  $_SESSION['flash_error'] = 'ID ruangan tidak valid.';
  header("Location: ruangan.php");
  exit;
}

// Cek apakah ruangan tersedia
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $id]);
$room = $stmt->fetch();

if (!$room) {
  $_SESSION['flash_error'] = 'Ruangan tidak ditemukan.';
  header("Location: ruangan.php");
  exit;
}

// Proses hapus ruangan
$delete = $pdo->prepare("DELETE FROM rooms WHERE id = :id");
$delete->execute(['id' => $id]);

$_SESSION['flash_success'] = 'Ruangan "' . htmlspecialchars($room['name']) . '" berhasil dihapus.';
header("Location: ruangan.php");
exit;
