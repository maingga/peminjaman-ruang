<?php
session_start();
require_once '../inc/db.php';

// Pastikan admin sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Validasi ID dari query string
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID admin tidak valid.");
}

$id = (int) $_GET['id'];
$adminId = $_SESSION['admin']['id'];

// Proteksi: jangan hapus akun sendiri
if ($id === $adminId) {
    die("Anda tidak bisa menghapus akun yang sedang login.");
}

// Cek apakah admin dengan ID tersebut ada
$stmt = $pdo->prepare("SELECT id FROM admins WHERE id = ?");
$stmt->execute([$id]);
$exists = $stmt->fetch();

if (!$exists) {
    die("Admin tidak ditemukan.");
}

// Hapus admin
$stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
$stmt->execute([$id]);

// Set flash message untuk notifikasi sukses
$_SESSION['flash_success'] = "Admin berhasil dihapus.";

header('Location: pengguna.php');
exit;
